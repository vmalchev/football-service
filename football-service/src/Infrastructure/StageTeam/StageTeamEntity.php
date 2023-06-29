<?php


namespace Sportal\FootballApi\Infrastructure\StageTeam;


use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class StageTeamEntity implements IStageTeamEntity, IDatabaseEntity
{

    private string $stageId;

    private string $teamId;

    public function __construct(string $stageId, string $teamId)
    {
        $this->stageId = $stageId;
        $this->teamId = $teamId;
    }

    public function getStageId(): string
    {
        return $this->stageId;
    }

    public function getTeamId(): string
    {
        return $this->teamId;
    }

    public function getDatabaseEntry(): array
    {
        return [
            StageTeamTableMapper::FIELD_STAGE_ID => $this->getStageId(),
            StageTeamTableMapper::FIELD_TEAM_ID => $this->getTeamId()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            StageTeamTableMapper::FIELD_STAGE_ID => $this->stageId,
            StageTeamTableMapper::FIELD_TEAM_ID => $this->teamId
        ];
    }

}