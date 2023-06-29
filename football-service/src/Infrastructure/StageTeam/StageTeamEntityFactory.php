<?php


namespace Sportal\FootballApi\Infrastructure\StageTeam;


use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntity;
use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntityFactory;

class StageTeamEntityFactory implements IStageTeamEntityFactory
{

    private string $stageId;

    private string $teamId;

    public function setStageId(string $stageId): StageTeamEntityFactory
    {
        $this->stageId = $stageId;
        return $this;
    }

    public function setTeamId(string $teamId): StageTeamEntityFactory
    {
        $this->teamId = $teamId;
        return $this;
    }

    public function create():IStageTeamEntity
    {
        return new StageTeamEntity(
            $this->stageId,
            $this->teamId
        );
    }

}