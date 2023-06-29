<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use Sportal\FootballApi\Domain\Match\IMatchEntity;

interface IMatchEventCollection
{
    /**
     * @param IMatchEventEntity[] $events
     * @return IMatchEventCollection
     */
    public function setEvents(array $events): IMatchEventCollection;

    /**
     * @return IMatchEventEntity[]
     */
    public function getEvents(): array;

    /**
     * @param IMatchEntity $matchEntity
     * @return IMatchEventCollection
     */
    public function setMatch(IMatchEntity $matchEntity): IMatchEventCollection;

    /**
     * @return IMatchEntity
     */
    public function getMatch(): IMatchEntity;

    public function withBlacklist(): IMatchEventCollection;

    public function upsert(): IMatchEventCollection;

}