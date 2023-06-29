<?php


namespace Sportal\FootballApi\Infrastructure\StageTeam;


use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\StageTeam\IStageTeamEntity;
use Sportal\FootballApi\Domain\StageTeam\IStageTeamRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Season\TournamentSeasonTeamTableMapper;

class StageTeamRepository implements IStageTeamRepository
{

    private Database $database;

    private DatabaseUpdate $databaseUpdate;

    private StageTeamTableMapper $stageTeamTableMapper;

    private IStageRepository $stageRepository;

    public function __construct(Database $database,
                                DatabaseUpdate $databaseUpdate,
                                StageTeamTableMapper $stageTeamTableMapper,
                                IStageRepository $stageRepository)
    {
        $this->database = $database;
        $this->databaseUpdate = $databaseUpdate;
        $this->stageTeamTableMapper = $stageTeamTableMapper;
        $this->stageRepository = $stageRepository;
    }

    private function findTeamIds(array $stageIds): array
    {
        $expr = $this->database->andExpression()->in(StageTeamTableMapper::FIELD_STAGE_ID, $stageIds);
        $query = $this->database
            ->createQuery($this->stageTeamTableMapper->getTableName(), [StageTeamTableMapper::FIELD_TEAM_ID])
            ->where($expr)
            ->distinct();
        return $this->database->getQueryResults($query, function ($data) {
            return $data[StageTeamTableMapper::FIELD_TEAM_ID];
        });
    }

    public function deleteByStageId($stageId): void
    {
        $this->databaseUpdate->delete(
            $this->stageTeamTableMapper->getTableName(), [StageTeamTableMapper::FIELD_STAGE_ID => $stageId]
        );
    }

    public function insert(IStageTeamEntity $stageTeamEntity)
    {
        $this->databaseUpdate->insert($this->stageTeamTableMapper->getTableName(), $stageTeamEntity);
    }

    public function upsertByStage(IStageEntity $stageEntity, array $teams)
    {
        $stageId = $stageEntity->getId();
        $this->deleteByStageId($stageId);

        foreach ($teams as $team) {
            $this->insert($team);
        }

        $this->upsertTournamentSeasonTeam($stageEntity);
    }

    private function upsertTournamentSeasonTeam(IStageEntity $stageEntity) {
        $seasonId = $stageEntity->getSeasonId();

        $this->databaseUpdate->delete(
            TournamentSeasonTeamTableMapper::TABLE_NAME,
            [TournamentSeasonTeamTableMapper::FIELD_TOURNAMENT_SEASON_ID => $seasonId]
        );

        $stages = $this->stageRepository->findBySeasonId($seasonId);
        $stageIds = array_map(fn($stage) => $stage->getId(), $stages);

        $teams = $this->findTeamIds($stageIds);

        foreach ($teams as $team) {
            $seasonTeamEntity = new SeasonTeamEntity(
                null,
                $seasonId,
                $team
            );

            $this->databaseUpdate->insertGeneratedId(TournamentSeasonTeamTableMapper::TABLE_NAME, $seasonTeamEntity);
        }
    }

}