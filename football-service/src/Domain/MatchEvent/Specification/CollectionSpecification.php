<?php


namespace Sportal\FootballApi\Domain\MatchEvent\Specification;


use Sportal\FootballApi\Domain\MatchEvent\Exception\InvalidMatchEventException;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventCollection;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventRepository;

class CollectionSpecification
{
    private IMatchEventRepository $matchEventRepository;

    /**
     * CollectionSpecification constructor.
     * @param IMatchEventRepository $matchEventRepository
     */
    public function __construct(IMatchEventRepository $matchEventRepository)
    {
        $this->matchEventRepository = $matchEventRepository;
    }

    /**
     * @param IMatchEventCollection $collection
     * @throws InvalidMatchEventException
     */
    public function validate(IMatchEventCollection $collection): void
    {
        $matchId = $collection->getMatch()->getId();
        $existingIds = array_map(fn($event) => $event->getId(), $this->matchEventRepository->findByMatchId($matchId));
        foreach ($collection->getEvents() as $event) {
            if ($event->getPrimaryPlayerId() !== null && $event->getPrimaryPlayer() === null) {
                throw new InvalidMatchEventException("Invalid primaryPlayerId {$event->getPrimaryPlayer()}");
            }
            if ($event->getSecondaryPlayerId() !== null && $event->getSecondaryPlayer() === null) {
                throw new InvalidMatchEventException("Invalid secondaryPlayerId {$event->getPrimaryPlayer()}");
            }
            if ($event->getId() !== null && !in_array($event->getId(), $existingIds)) {
                throw new InvalidMatchEventException("MatchEvent:{$event->getId()} is not part of the match:{$matchId}");
            }
            if ($event->getMatchId() !== $matchId) {
                throw new InvalidMatchEventException("MatchEvent is not part of the match:{$matchId}");
            }
        }
    }

}