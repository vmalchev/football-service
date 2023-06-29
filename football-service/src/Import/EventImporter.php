<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Round\RoundType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\Tournament;
use Sportal\FootballApi\Model\TournamentSeasonStage;
use Sportal\FootballApi\Repository\EventOrderRepository;
use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\RefereeRepository;
use Sportal\FootballApi\Repository\StageGroupRepository;
use Sportal\FootballApi\Repository\TeamRepository;
use Sportal\FootballApi\Repository\TournamentSeasonStageRepository;
use Sportal\FootballApi\Repository\VenueRepository;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballFeedCommon\EventFeedInterface;

class EventImporter extends MappableImporter
{

    /**
     *
     * @var EventFeedInterface
     */
    private $feed;

    /**
     *
     * @var TournamentSeasonStageRepository
     */
    private $stageRepository;

    /**
     *
     * @var EventStatusImporter
     */
    private $statusImporter;

    /**
     *
     * @var JobDispatcherInterface
     */
    private $dispatcher;

    /**
     *
     * @var EventRepository
     */
    protected $repository;

    /**
     *
     * @var TeamRepository
     */
    protected $teamRepository;

    /**
     *
     * @var VenueRepository
     */
    protected $venueRepository;

    /**
     *
     * @var RefereeRepository
     */
    protected $refereeRepository;

    protected $stageGroupRepository;

    protected $eventOrder;

    public function __construct(
        EventRepository $repository,
        MappingRepositoryInterface $mapping,
        LoggerInterface $logger,
        EventFeedInterface $eventFeed,
        TournamentSeasonStageRepository $stageRepository,
        EventStatusImporter $statusImporter,
        JobDispatcherInterface $dispatcher,
        TeamRepository $teamRepository,
        VenueRepository $venueRepository,
        RefereeRepository $refereeRepository,
        StageGroupRepository $stageGroupRepository,
        EventOrderRepository $eventOrder
    )
    {
        parent::__construct($repository, $mapping, $logger);
        $this->feed = $eventFeed;
        $this->stageRepository = $stageRepository;
        $this->statusImporter = $statusImporter;
        $this->dispatcher = $dispatcher;
        $this->teamRepository = $teamRepository;
        $this->venueRepository = $venueRepository;
        $this->refereeRepository = $refereeRepository;
        $this->stageGroupRepository = $stageGroupRepository;
        $this->eventOrder = $eventOrder;
    }

    public function importEvents($fromDateTime, $toDateTime)
    {
        $tournamentIds = $this->mapping->getRemoteIds(Tournament::class);
        $events = $this->feed->getEvents($tournamentIds, $fromDateTime, $toDateTime);
        $this->import($events);
    }

    public function importLive()
    {
        $tournamentIds = $this->mapping->getRemoteIds(Tournament::class);
        $events = $this->feed->getLive($tournamentIds);
        $this->import($events);
    }

    public function importStage(TournamentSeasonStage $stage)
    {
        $events = null;
        if ($stage->hasGroups()) {
            $groups = $this->stageGroupRepository->findByStage($stage->getId());
            $ids = [];
            foreach ($groups as $group) {
                $remoteId = $this->mapping->getRemoteId($this->stageGroupRepository->getModelClass(), $group->getId());
                if ($remoteId !== null) {
                    $ids[] = $remoteId;
                }
            }
            if (count($ids) > 0) {
                $events = $this->feed->getStageGroupEvents($ids);
            }
        } else {
            $stageId = $this->mapping->getRemoteId($this->stageRepository->getModelClass(), $stage->getId());
            if ($stageId !== null) {
                $events = $this->feed->getTournamentStageEvents($stageId);
            }
        }
        if (!empty($events)) {
            $this->import($events);
        }

        //CleanUP
        $this->cleanUpDeleted($events, $stage->getId());

    }

    private function cleanUpDeleted(array $events, $stageId)
    {
        $domainIds = [];

        foreach ($events as $event) {
            $ownId = $this->mapping->getOwnId(Event::class, $event['id']);
            if (is_null($ownId)) {
                continue;
            }
            $domainIds[] = $ownId;
        }

        $events = $this->repository->findByStage($stageId);

        $eventsDiff = array_filter($events, function (Event $event) use ($domainIds) {
            return !in_array($event->getId(), $domainIds);
        });

        foreach ($eventsDiff as $event) {
            if (!$this->blacklistRepository->exists(new BlacklistKey(BlacklistType::ENTITY(), BlacklistEntityName::MATCH(), $event->getId(), null))) {
                $this->logger->info('Deleting:' . $event->getId());
                $this->repository->delete($event);
            } else {
                $this->logger->info("Skipping delete match:{$event->getId()} due to blacklist");
            }
        }
    }

    private function import(array $events)
    {
        foreach ($events as $eventArr) {
            $tournamentStageId = null;
            if (isset($eventArr['stage_group_id'])) {
                $ownId = $this->mapping->getOwnId($this->stageGroupRepository->getModelClass(),
                    $eventArr['stage_group_id']);
                if ($ownId !== null && ($stageGroup = $this->stageGroupRepository->find($ownId)) !== null) {
                    $tournamentStageId = $stageGroup->getTournamentSeasonStageId();
                    $eventArr['stage_group'] = $stageGroup;
                }
            } else {
                $tournamentStageId = $this->mapping->getOwnId(TournamentSeasonStage::class,
                    $eventArr['tournament_season_stage_id']);
            }

            if ($tournamentStageId !== null) {

                $eventArr['tournament_season_stage'] = $this->stageRepository->find($tournamentStageId)->clonePartial();
                $eventArr['event_status'] = $this->statusImporter->import($eventArr['event_status']);

                $eventArr['home_team'] = $this->getTeam($eventArr['home_id']);
                $eventArr['away_team'] = $this->getTeam($eventArr['away_id']);
                if (isset($eventArr['venue_id'])) {
                    $eventArr['venue'] = $this->getVenue($eventArr['venue_id']);
                }

                if (isset($eventArr['referee_id'])) {
                    $eventArr['referee'] = $this->getReferee($eventArr['referee_id']);
                }

                if (isset($eventArr['round_type_id'])) {
                    $ownId = $this->mapping->getOwnId(RoundType::class, $eventArr['round_type_id']);
                    if ($ownId === null) {
                        $this->dispatcher->dispatch('Import\RoundType',
                            [
                                $eventArr['round_type_id']
                            ]
                        );
                    }
                    $eventArr['round_type_id'] = $ownId;
                }

                $event = $this->repository->createObject($eventArr);
                $this->importModel($event, $eventArr['id'],
                    function (Event $model, $changes) use ($eventArr) {
                        $id = $model->getId();
                        if (count($changes) === 0 || (in_array('event_status_id', $changes) && $model->getEventStatus()
                                    ->isFinished())) {
                            $this->logger->info(
                                NameUtil::shortClassName(get_class($this)) . ": " . $model->getName() .
                                " is created/finished, run final imports");
                            $this->dispatcher->dispatch('Import\EventPlayer',
                                [
                                    $id
                                ]);
                            $this->dispatcher->dispatch('Import\EventTeamStats',
                                [
                                    $id
                                ]);
                            $this->dispatcher->dispatch('Update\TeamForm',
                                [
                                    $id
                                ]);
                        }

                        if ($model->getVenue() === null && isset($eventArr['venue_id'])) {
                            $this->dispatcher->dispatch('Import\EventVenue',
                                [
                                    $id,
                                    $eventArr['venue_id']
                                ]);
                        }

                        if ($model->getReferee() === null && isset($eventArr['referee_id'])) {
                            $this->dispatcher->dispatch('Import\EventReferee',
                                [
                                    $id,
                                    $eventArr['referee_id']
                                ]);
                        }

                        if (count($changes) === 0 || in_array('incidents', $changes)) {
                            $this->dispatcher->dispatch('Import\EventIncident',
                                [
                                    $id
                                ]);
                        }
                        if ($model->getHomeTeam() === null || $model->getAwayTeam() === null) {
                            $this->dispatcher->dispatch('Import\EventTeam',
                                [
                                    $eventArr['home_id'],
                                    $eventArr['away_id'],
                                    $id
                                ]);
                        }
                    });
            }
        }
        $this->statusImporter->handleChanges();
        $this->handleChanges(
            function ($changedKeys, $created) {
                $this->eventOrder->addEvents($created);
            });
    }

    protected function getReferee($feedId)
    {
        if (($ownId = $this->mapping->getOwnId($this->refereeRepository->getModelClass(), $feedId)) !== null) {
            $referee = $this->refereeRepository->find($ownId);
            if ($referee !== null) {
                return $referee->clonePartial();
            }
        }
        return null;
    }

    protected function getVenue($feedId)
    {
        if (($ownId = $this->mapping->getOwnId($this->venueRepository->getModelClass(), $feedId)) !== null) {
            $venue = $this->venueRepository->find($ownId);
            if ($venue !== null) {
                return $venue->clonePartial();
            }
        }
        return null;
    }

    protected function getTeam($feedId)
    {
        if (($ownId = $this->mapping->getOwnId($this->teamRepository->getModelClass(), $feedId)) !== null) {
            $team = $this->teamRepository->find($ownId);
            if ($team !== null) {
                return $this->teamRepository->clonePartial($team);
            }
        }
        return null;
    }
}

