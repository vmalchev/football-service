<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\MatchEvent\Exception\InvalidMatchEventException;


interface IMatchEventCollectionBuilder
{
    /**
     * @param IMatchEntity $match
     * @param IMatchEventEntity[] $events
     * @return IMatchEventCollection
     * @throws InvalidMatchEventException
     */
    public function build(IMatchEntity $match, array $events): IMatchEventCollection;
}