<?php

namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Database\ModelInterface;
use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistKey;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Repository\CoachRepository;
use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballApi\Repository\LineupRepository;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballFeedCommon\LineupFeedInterface;

class LineupImporter
{

    protected $repository;

    protected $mappings;

    protected $coachRepository;

    /**
     *
     * @var LineupFeedInterface[]
     */
    protected $feeds;

    protected $eventPlayerImporter;

    protected $logger;

    protected $dispatcher;

    protected $eventRepository;

    protected IBlacklistRepository $blacklistRepository;

    public function __construct(LineupRepository $lineupRepository, MappingRepositoryContainer $mappings,
                                CoachRepository $coachRepository, FeedContainer $feeds, EventPlayerImporter $eventPlayerImporter,
                                JobDispatcherInterface $dispatcher, LoggerInterface $logger, EventRepository $eventRepository,
                                IBlacklistRepository $blacklistRepository)
    {
        $this->repository = $lineupRepository;
        $this->mappings = $mappings;
        $this->coachRepository = $coachRepository;
        $this->feeds = $feeds;
        $this->eventPlayerImporter = $eventPlayerImporter;
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->eventRepository = $eventRepository;
        $this->blacklistRepository = $blacklistRepository;
    }

    public function import($eventId, $sourceName)
    {
        $mapping = $this->mappings->get($sourceName);
        $feedId = $mapping->getRemoteId(Event::class, $eventId);

        if ($feedId !== null) {
            $blacklistKey = new BlacklistKey(
                BlacklistType::RELATION(),
                BlacklistEntityName::MATCH(),
                $eventId,
                'lineup'
            );

            if ($this->blacklistRepository->exists($blacklistKey)) {
                return;
            }

            $lineupArr = $this->feeds[$sourceName]->getLineup($feedId);
            $event = $this->eventRepository->find($eventId);

            if (empty($lineupArr) || $event === null) {
                return;
            }

            $changedPlayers = 0;
            if (!empty($lineupArr['players'])) {
                $changedPlayers = $this->eventPlayerImporter->importPlayers($eventId, $lineupArr['players'],
                    $sourceName);
                if (!$event->getLineupAvaible()) {
                    $this->dispatcher->dispatch('Update\EventLineupAvailable', [
                        $event->getId()
                    ]);
                }
            }

            $lineupArr['event_id'] = $eventId;

            $lineupArr['home_team_id'] = $event->getHomeId();
            $lineupArr['away_team_id'] = $event->getAwayId();

            if (!empty($lineupArr['home_coach_id'])) {
                $lineupArr['home_coach'] = $this->coachRepository->getPartialFromFeed($lineupArr['home_coach_id'],
                    $mapping);
            }
            if (!empty($lineupArr['away_coach_id'])) {
                $lineupArr['away_coach'] = $this->coachRepository->getPartialFromFeed($lineupArr['away_coach_id'],
                    $mapping);
            }
            $lineup = $this->importByPk($this->repository->createObject($lineupArr), $eventId, $changedPlayers);
            if ($lineup->getHomeCoach() === null && !empty($lineupArr['home_coach_id'])) {
                $this->dispatcher->dispatch('Import\LineupCoach',
                    [
                        $lineup->getEventId(),
                        $lineupArr['home_coach_id'],
                        true,
                        $sourceName
                    ]);
            }
            if ($lineup->getAwayCoach() === null && !empty($lineupArr['away_coach_id'])) {
                $this->dispatcher->dispatch('Import\LineupCoach',
                    [
                        $lineup->getEventId(),
                        $lineupArr['away_coach_id'],
                        false,
                        $sourceName
                    ]);
            }
        }
    }

    public function importRecent(\DateTime $after, $sourceName)
    {
        $recentEventIds = $this->feeds[$sourceName]->getRecentlyUpdated($after);
        foreach ($recentEventIds as $remoteId) {
            $eventId = $this->mappings->get($sourceName)->getOwnId(Event::class, $remoteId);
            if ($eventId !== null) {
                $this->import($eventId, $sourceName);
            }
        }
    }

    /**
     *
     * @param ModelInterface $updated
     * @param unknown $primaryKey
     * @param unknown $changedPlayers
     * @return \Sportal\FootballApi\Model\Lineup
     */
    protected function importByPk(ModelInterface $updated, $primaryKey, $changedPlayers)
    {
        $existing = $this->repository->find($primaryKey);
        if ($existing === null) {
            $this->repository->create($updated);
            $this->repository->flush();
            $this->logger->info('LineupImporter: Create Lineup:' . $updated->getEventId());
            return $updated;
        }

        $changes = $this->repository->getChanges($existing, $updated);
        if ($changedPlayers > 0 || !empty($changes)) {
            $this->repository->update($existing, $updated);
            $this->repository->flush();
            $this->logger->info('LineupImporter: Update:' . $updated->getEventId() . " " . implode('-', $changes));
            return $updated;
        }

        return $existing;
    }
}