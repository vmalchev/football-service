<?php


namespace Sportal\FootballApi\Domain\TeamSquad;

use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamPlayerRepository
{
    /**
     * @param string $playerId
     * @param TeamSquadStatus|null $squadStatus
     * @return ITeamPlayerEntity[]
     */
    public function findByPlayer(string $playerId, ?TeamSquadStatus $squadStatus): array;

    /**
     * @param ITeamEntity $teamEntity
     * @param TeamSquadStatus|null $squadStatus
     * @return ITeamPlayerEntity[]
     */
    public function findByTeam(ITeamEntity $teamEntity, ?TeamSquadStatus $squadStatus = null): array;

    /**
     * @param ITeamPlayerEntity[] $teamPlayerEntities
     * @return ITeamPlayerEntity[]
     */
    public function upsert(array $teamPlayerEntities): array;

    /**
     * @param string $teamId
     */
    public function deleteByTeam(string $teamId): void;
}