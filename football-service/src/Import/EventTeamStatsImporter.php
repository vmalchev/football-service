<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\EventTeamStatsRepository;
use Sportal\FootballApi\Repository\MappingRepositoryInterface;
use Sportal\FootballApi\Repository\EventRepository;
use Sportal\FootballFeedCommon\EventTeamStatsFeedInterface;
use Psr\Log\LoggerInterface;

class EventTeamStatsImporter extends MappableImporter
{

    /**
     *
     * @var EventTeamStatsRepository
     */
    protected $repository;

    /**
     *
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     *
     * @var EventTeamStatsFeedInterface
     */
    protected $feed;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Import\MappableImporter::__construct()
     */
    public function __construct(EventTeamStatsRepository $repository, MappingRepositoryInterface $mapping,
        LoggerInterface $logger, EventRepository $eventRepository, EventTeamStatsFeedInterface $feed)
    {
        // TODO Auto-generated method stub
        parent::__construct($repository, $mapping, $logger);
        $this->eventRepository = $eventRepository;
        $this->feed = $feed;
    }

    public function import($eventId)
    {
        $event = $this->eventRepository->find($eventId);
        if ($event !== null) {
            $feedEventId = $this->mapping->getRemoteId($this->eventRepository->getModelClass(), $event->getId());
            $stats = $this->feed->getStats($feedEventId);
            foreach ($stats as $statArray) {
                $statArray['event_id'] = $event->getId();
                if ($statArray['home_team'] && $event->getHomeTeam() !== null) {
                    $statArray['team'] = $event->getHomeTeam();
                } elseif (! $statArray['home_team'] && $event->getAwayTeam() !== null) {
                    $statArray['team'] = $event->getAwayTeam();
                }
                $teamStats = $this->repository->createObject($statArray);
                $this->importModel($teamStats, $statArray['id']);
                if (! $event->getTeamstatsAvailable()) {
                    $this->eventRepository->setTeamstatsAvailable($event);
                }
            }
            $this->handleChanges();
        }
    }
}