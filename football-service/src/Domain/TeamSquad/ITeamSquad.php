<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamSquad
{
    /**
     * @return ITeamEntity
     */
    public function getTeam(): ITeamEntity;

    /**
     * @return ITeamPlayerEntity[]
     */
    public function getPlayers(): array;

    /**
     * @param ITeamPlayerEntity[] $teamPlayers
     * @return ITeamSquad
     */
    public function setPlayers(array $teamPlayers): ITeamSquad;

    /**
     * @param ITeamEntity $teamEntity
     * @return ITeamSquad
     */
    public function setTeam(ITeamEntity $teamEntity): ITeamSquad;
}