<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;

interface ILineupPlayerEntity
{
    public function getMatchId(): string;

    public function getPlayerName(): ?string;

    public function getTypeId(): ?string;

    public function getType(): ?ILineupPlayerTypeEntity;

    public function getPlayer(): ?IPlayerEntity;

    public function getPlayerId(): ?string;

    public function getPositionX(): ?int;

    public function getPositionY(): ?int;

    public function getShirtNumber(): ?int;

    public function getHomeTeam(): bool;
}