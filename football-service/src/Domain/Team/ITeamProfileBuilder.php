<?php


namespace Sportal\FootballApi\Domain\Team;


interface ITeamProfileBuilder
{
    /**
     * @param ITeamEntity $teamEntity
     * @return ITeamProfile
     */
    public function build(ITeamEntity $teamEntity): ITeamProfile;
}