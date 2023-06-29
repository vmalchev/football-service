<?php


namespace Sportal\FootballApi\Domain\StageTeam;


use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface IStageTeamModel
{

    public function setStageTeams(array $stageTeams, IStageEntity $stageEntity): StageTeamModel;

    public function getStageTeams(): array;

    public function withBlacklist(): StageTeamModel;

    public function update(): void;

}