<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamCoachRepository
{
    /**
     * @param ITeamEntity $teamEntity
     * @param TeamSquadStatus|null
     * @return ITeamCoachEntity[]
     */
    public function findByTeam(ITeamEntity $teamEntity, ?TeamSquadStatus $squadStatus = null): array;

    /**
     * @param ITeamEntity $team
     * @param ITeamCoachEntity[] $coaches
     * @return mixed
     */
    public function upsertByTeam(ITeamEntity $team, array $coaches): void;
}