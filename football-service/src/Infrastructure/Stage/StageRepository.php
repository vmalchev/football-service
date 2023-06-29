<?php


namespace Sportal\FootballApi\Infrastructure\Stage;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Stage\IStageRepository;
use Sportal\FootballApi\Domain\Stage\StageFilter;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Season\SeasonTableMapper;

class StageRepository implements IStageRepository
{

    private Database $db;

    private DatabaseUpdate $databaseUpdate;

    private StageTableMapper $tableMapper;

    /**
     * StageRepository constructor.
     * @param Database $db
     * @param StageTableMapper $tableMapper
     * @param DatabaseUpdate $databaseUpdate
     */
    public function __construct(Database         $db,
                                StageTableMapper $tableMapper,
                                DatabaseUpdate   $databaseUpdate)
    {
        $this->db = $db;
        $this->tableMapper = $tableMapper;
        $this->databaseUpdate = $databaseUpdate;
    }


    public function findById(string $id): ?IStageEntity
    {
        $expr = $this->db->andExpression()->eq(StageTableMapper::FIELD_ID, $id);
        $query = $this->db->createQuery(StageTableMapper::TABLE_NAME)
            ->where($expr)
            ->addRelations($this->tableMapper->getRelations());
        return $this->db->getSingleResult($query, [$this->tableMapper, 'toEntity']);
    }

    public function findBySeasonId(string $id): array
    {
        return $this->findByFilter(StageFilter::create()->setSeasonIds([$id]));
    }

    /**
     * @inheritDoc
     */
    public function findByFilter(StageFilter $filter): array
    {
        $expr = $this->db->andExpression();
        if (!empty($filter->getSeasonIds())) {
            $expr->in(StageTableMapper::FIELD_SEASON_ID, $filter->getSeasonIds());
        } elseif (!empty($filter->getTournamentIds())) {
            $expr->in(SeasonTableMapper::FIELD_TOURNAMENT_ID, $filter->getTournamentIds(), SeasonTableMapper::TABLE_NAME);
        }
        if ($expr->count() == 0) {
            throw new InvalidArgumentException("Invalid StageFilter");
        }

        $query = $this->db->createQuery(StageTableMapper::TABLE_NAME)
            ->where($expr)
            ->addRelations($this->tableMapper->getRelations())
            ->addOrderBy(SeasonTableMapper::FIELD_NAME, SortDirection::from(SortDirection::ASC), SeasonTableMapper::TABLE_NAME)
            ->addOrderBy(StageTableMapper::FIELD_ORDER_IN_SEASON, SortDirection::from(SortDirection::ASC));

        return $this->db->getQueryResults($query, [$this->tableMapper, 'toEntity']);
    }

    public function insert(IStageEntity $stageEntity): IStageEntity
    {
        return $this->databaseUpdate->insertGeneratedId($this->tableMapper->getTableName(), $stageEntity);
    }

    public function update(IStageEntity $stageEntity): void
    {
        $this->databaseUpdate->update($this->tableMapper->getTableName(), $stageEntity);
    }

    public function delete($id): void
    {
        $this->databaseUpdate->delete($this->tableMapper->getTableName(), [StageTableMapper::FIELD_ID => $id]);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $id): bool
    {
        return $this->db->exists($this->tableMapper->getTableName(), [StageTableMapper::FIELD_ID => $id]);
    }

}