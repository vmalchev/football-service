<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\EventIncident;
use Sportal\FootballApi\Model\Player;
use Sportal\FootballApi\Model\Team;
use Sportal\FootballApi\Repository\EventIncidentRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\PlayerRepository;
use Sportal\FootballFeedCommon\EventIncidentFeedInterface;
use Sportal\FootballFeedCommon\FeedContainer;

class EventIncidentImporter extends Importer
{

    /**
     *
     * @var EventIncidentFeedInterface[]
     */
    protected $feeds;

    /**
     *
     * @var JobDispatcherInterface
     */
    protected $dispatcher;

    /**
     *
     * @var PlayerRepository
     */
    protected $playerRepository;

    /**
     *
     * @var EventIncidentRepository
     */
    protected $repository;

    public function __construct(EventIncidentRepository $repository, MappingRepositoryContainer $mappings,
                                LoggerInterface $logger, FeedContainer $feeds, JobDispatcherInterface $dispatcher,
                                PlayerRepository $playerRepository)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->feeds = $feeds;
        $this->dispatcher = $dispatcher;
        $this->playerRepository = $playerRepository;
    }

    public function importEventIncidents($eventId, array $incidents, $sourceName = null)
    {
        if ($this->blacklistRepository->exists(new BlacklistKey(BlacklistType::RELATION(), BlacklistEntityName::MATCH(), $eventId, 'events'))) {
            return;
        }
        $existing = $this->repository->findByEvent($eventId);
        $updated = [];
        foreach ($incidents as $incidentArr) {
            $incidentArr['event_id'] = $eventId;
            $incidentArr['team_id'] = $this->mappings->get($sourceName)->getOwnId(Team::class, $incidentArr['team_id']);
            $incidentArr['player'] = $this->getPlayer($incidentArr['player_id'], $sourceName);
            if (isset($incidentArr['rel_player_id'])) {
                $incidentArr['rel_player'] = $this->getPlayer($incidentArr['rel_player_id'], $sourceName);
            }
            $model = $this->repository->createObject($incidentArr);
            $onUpdate = function (EventIncident $model, $changes) use ($incidentArr) {
                if ($model->getPlayer() === null ||
                    ($model->getRelPlayer() === null && isset($incidentArr['rel_player_id']))) {
                    $this->dispatcher->dispatch('Import\EventIncidentPlayer',
                        [
                            $model->getPlayer() === null ? $incidentArr['player_id'] : null,
                            $model->getRelPlayer() === null && isset($incidentArr['rel_player_id']) ? $incidentArr['rel_player_id'] : null,
                            $model->getId()
                        ]);
                }
            };
            $updated[] = $this->importModel($model, $incidentArr['id'], $sourceName, $onUpdate);
        }

        foreach ($existing as $incident) {
            $matching = array_filter($updated,
                function ($value) use ($incident) {
                    return $value->getId() == $incident->getId();
                });
            if (empty($matching)) {
                $this->repository->delete($incident);
            }
        }

        $this->handleChanges(function () {
        });
    }

    public function importRecent(\DateTime $after, $sourceName)
    {
        $recentEventIds = $this->feeds[$sourceName]->getRecentlyUpdated($after);
        foreach ($recentEventIds as $remoteId) {
            $eventId = $this->mappings->get($sourceName)->getOwnId(Event::class, $remoteId);
            if ($eventId) {
                $incidents = $this->feeds[$sourceName]->getEventIncidents($remoteId);
                $this->importEventIncidents($eventId, $incidents);
            }
        }
    }

    public function import($eventId, $sourceName = null)
    {
        $remoteId = $this->mappings->get($sourceName)->getRemoteId(Event::class, $eventId);
        if ($remoteId !== null) {
            $incidents = $this->feeds[$sourceName]->getEventIncidents($remoteId);
            if (!empty($incidents)) {
                $this->importEventIncidents($eventId, $incidents, $sourceName);
                $this->handleChanges(
                    function ($changedKeys, $createdModels) use ($eventId) {
                        $this->repository->refreshEvent($changedKeys, $createdModels, $eventId);
                    });
            }
        }
    }

    protected function getPlayer($remoteId, $sourceName = null)
    {
        $ownId = $this->mappings->get($sourceName)->getOwnId(Player::class, $remoteId);
        if ($ownId !== null) {
            $player = $this->playerRepository->find($ownId);
            if ($player !== null) {
                return $this->playerRepository->clonePartial($player);
            }
        }
        return null;
    }
}
