<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;

class MatchEventCollectionBuilder implements IMatchEventCollectionBuilder
{
    private IPlayerRepository $playerRepository;

    private IMatchEventEntityFactory $matchEventFactory;

    private IMatchEventCollection $collection;

    private IMatchScoreBuilderFactory $scoreBuilderFactory;

    private Specification\CollectionSpecification $collectionSpecification;

    /**
     * MatchEventCollectionBuilder constructor.
     * @param IPlayerRepository $playerRepository
     * @param IMatchEventEntityFactory $matchEventFactory
     * @param IMatchEventCollection $collection
     * @param IMatchScoreBuilderFactory $scoreBuilderFactory
     * @param Specification\CollectionSpecification $collectionSpecification
     */
    public function __construct(IPlayerRepository $playerRepository,
                                IMatchEventEntityFactory $matchEventFactory,
                                IMatchEventCollection $collection,
                                IMatchScoreBuilderFactory $scoreBuilderFactory,
                                Specification\CollectionSpecification $collectionSpecification)
    {
        $this->playerRepository = $playerRepository;
        $this->matchEventFactory = $matchEventFactory;
        $this->collection = $collection;
        $this->scoreBuilderFactory = $scoreBuilderFactory;
        $this->collectionSpecification = $collectionSpecification;
    }

    /**
     * @param IMatchEntity $match
     * @param IMatchEventEntity[] $events
     * @return IMatchEventCollection
     * @throws Exception\InvalidMatchEventException
     */
    public function build(IMatchEntity $match, array $events): IMatchEventCollection
    {
        $playerIds = [];
        foreach ($events as $event) {
            if ($event->getPrimaryPlayerId() !== null) {
                $playerIds[] = $event->getPrimaryPlayerId();
            }
            if ($event->getSecondaryPlayerId() !== null) {
                $playerIds[] = $event->getSecondaryPlayerId();
            }
        }
        $playerCollection = $this->playerRepository->findByIds(array_unique($playerIds));
        $scoreBuilder = $this->scoreBuilderFactory->create();
        usort($events, function (IMatchEventEntity $firstEvent, IMatchEventEntity $secondEvent) {
            $sort = $firstEvent->getMinute() - $secondEvent->getMinute();
            if ($sort === 0) {
                $sort = (int)$firstEvent->getSortOrder() - (int)$secondEvent->getSortOrder();
            }
            return $sort;
        });
        $builtEvents = [];
        foreach ($events as $event) {
            $factory = $this->matchEventFactory->setFrom($event);
            if ($event->getPrimaryPlayerId() !== null) {
                $factory->setPrimaryPlayer($playerCollection->getById($event->getPrimaryPlayerId()));
            }
            if ($event->getSecondaryPlayerId() !== null) {
                $factory->setSecondaryPlayer($playerCollection->getById($event->getSecondaryPlayerId()));
            }
            $scoreBuilder->addEvent($event);
            if (MatchEventType::isGoal($event->getEventType())) {
                $totalScore = $scoreBuilder->getTotalScore();
                $factory->setGoalHome($totalScore->getHome())->setGoalAway($totalScore->getAway());
            }
            if ($event->getTeamPosition()->equals(TeamPositionStatus::HOME())) {
                $factory->setTeamId($match->getHomeTeamId());
            } else {
                $factory->setTeamId($match->getAwayTeamId());
            }
            $builtEvents[] = $factory->create();
        }
        $eventCollection = $this->collection->setMatch($match)->setEvents($builtEvents);
        $this->collectionSpecification->validate($eventCollection);
        return $eventCollection;
    }

}