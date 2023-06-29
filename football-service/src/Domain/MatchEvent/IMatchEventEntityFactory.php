<?php


namespace Sportal\FootballApi\Domain\MatchEvent;

use Sportal\FootballApi\Domain\Player\IPlayerEntity;


interface IMatchEventEntityFactory
{
    public function setId(string $id): IMatchEventEntityFactory;

    public function setMatchId(string $matchId): IMatchEventEntityFactory;

    public function setEventType(MatchEventType $eventType): IMatchEventEntityFactory;

    public function setTeamPosition(TeamPositionStatus $teamPosition): IMatchEventEntityFactory;

    public function setTeamId(?string $teamId): IMatchEventEntityFactory;

    public function setMinute(int $minute): IMatchEventEntityFactory;

    public function setPrimaryPlayer(?IPlayerEntity $primaryPlayer): IMatchEventEntityFactory;

    public function setPrimaryPlayerId(?string $primaryPlayerId): IMatchEventEntityFactory;

    public function setSecondaryPlayer(?IPlayerEntity $secondaryPlayer): IMatchEventEntityFactory;

    public function setSecondaryPlayerId(?string $secondaryPlayerId): IMatchEventEntityFactory;

    public function setGoalHome(?int $goalHome): IMatchEventEntityFactory;

    public function setGoalAway(?int $goalAway): IMatchEventEntityFactory;

    public function setSortOrder(?int $sortOrder): IMatchEventEntityFactory;

    public function setEmpty(): IMatchEventEntityFactory;

    public function setFrom(IMatchEventEntity $entity): IMatchEventEntityFactory;

    public function create(): IMatchEventEntity;
}