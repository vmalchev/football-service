<?php


namespace Sportal\FootballApi\Domain\StageTeam;



use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface IStageTeamRepository
{

    public function upsertByStage(IStageEntity $stageEntity, array $teams);

}