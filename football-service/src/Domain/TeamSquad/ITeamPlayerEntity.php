<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamPlayerEntity
{
    public function getId(): ?string;

    public function getPlayerId(): string;

    public function getPlayer(): ?IPlayerEntity;

    public function getTeamId(): string;

    public function getStartDate(): ?DateTimeInterface;

    public function getEndDate(): ?DateTimeInterface;

    public function getStatus(): TeamSquadStatus;

    public function getShirtNumber(): ?int;

    public function getContractType(): PlayerContractType;

    public function getTeam(): ?ITeamEntity;
}

