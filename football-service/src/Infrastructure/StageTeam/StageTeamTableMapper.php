<?php


namespace Sportal\FootballApi\Infrastructure\StageTeam;


use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntityFactory;

class StageTeamTableMapper
{

    public const TABLE_NAME = 'tournament_season_stage_team';
    public const FIELD_STAGE_ID = 'tournament_season_stage_id';
    public const FIELD_TEAM_ID = 'team_id';

    private IStageTeamEntityFactory $stageTeamEntityFactory;

    public function __construct(IStageTeamEntityFactory $stageTeamEntityFactory)
    {
        $this->stageTeamEntityFactory = $stageTeamEntityFactory;
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_STAGE_ID,
            self::FIELD_TEAM_ID
        ];
    }

    public function toEntity(array $data): object
    {
        return $this->stageTeamEntityFactory
            ->setTeamId($data['team_id'])
            ->setStageId($data['tournament_season_stage_id'])
            ->create();

    }

}