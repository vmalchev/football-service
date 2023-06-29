<?php


namespace Sportal\FootballApi\Domain\MatchEvent;

use Sportal\FootballApi\Domain\Player\IPlayerEntity;

interface IMatchEventEntity
{
    public function getId(): ?string;

    public function getMatchId(): string;

    public function getEventType(): MatchEventType;

    public function getTeamPosition(): TeamPositionStatus;

    public function getMinute(): int;

    public function getTeamId(): ?string;

    public function getPrimaryPlayer(): ?IPlayerEntity;

    public function getPrimaryPlayerId(): ?string;

    public function getSecondaryPlayer(): ?IPlayerEntity;

    public function getSecondaryPlayerId(): ?string;

    public function getGoalHome(): ?int;

    public function getGoalAway(): ?int;

    public function getSortOrder(): ?int;
}