<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ITeamCoachModel
{
    public function setTeamCoaches(ITeamEntity $team, array $coaches): ITeamCoachModel;

    public function withBlacklist(): ITeamCoachModel;

    public function upsert(): ITeamCoachModel;
}