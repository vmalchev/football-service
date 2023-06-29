<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamPlayerEntityFactory
{
    public function setEmpty(): ITeamPlayerEntityFactory;

    public function setId(?string $id): ITeamPlayerEntityFactory;

    public function setFrom(ITeamPlayerEntity $entity): ITeamPlayerEntityFactory;

    public function setPlayerId(string $playerId): ITeamPlayerEntityFactory;

    public function setPlayer(?IPlayerEntity $player): ITeamPlayerEntityFactory;

    public function setTeamId(string $teamId): ITeamPlayerEntityFactory;

    public function setStartDate(?DateTimeInterface $startDate): ITeamPlayerEntityFactory;

    public function setEndDate(?DateTimeInterface $endDate): ITeamPlayerEntityFactory;

    public function setStatus(TeamSquadStatus $status): ITeamPlayerEntityFactory;

    public function setShirtNumber(?int $shirtNumber): ITeamPlayerEntityFactory;

    public function setContractType(PlayerContractType $playerContractType): ITeamPlayerEntityFactory;

    public function setTeam(?ITeamEntity $teamEntity): ITeamPlayerEntityFactory;

    public function create(): ITeamPlayerEntity;
}