<?php


namespace Sportal\FootballApi\Infrastructure\Season;

use Sportal\FootballApi\Database\Query\SortDirection;
use Sportal\FootballApi\Domain\Season\ISeasonCollection;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Season\ISeasonRepository;
use Sportal\FootballApi\Domain\Season\SeasonFilter;
use Sportal\FootballApi\Domain\Season\SeasonStatus;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Tournament\TournamentTableMapper;

class SeasonRepository implements ISeasonRepository
{
    private Database $db;

    private DatabaseUpdate $databaseUpdate;

    private SeasonTableMapper $mapper;

    /**
     * @param Database $db
     * @param SeasonTableMapper $tableMapper
     * @param DatabaseUpdate $databaseUpdate
     */
    public function __construct(Database $db, SeasonTableMapper $tableMapper,
                                DatabaseUpdate $databaseUpdate)
    {
        $this->db = $db;
        $this->mapper = $tableMapper;
        $this->databaseUpdate = $databaseUpdate;
    }


    public function listByFilter(SeasonFilter $filter): array
    {
        $whereExpr = $this->db->andExpression();

        $query = $this->db->createQuery($this->mapper->getTableName())
            ->where($whereExpr)
            ->addJoin($this->mapper->joinTournament())
            ->addOrderBy(SeasonTableMapper::FIELD_NAME, SortDirection::DESC)
            ->addOrderBy(SeasonTableMapper::FIELD_TOURNAMENT_ID, SortDirection::ASC);

        if ($filter->getTournamentId() !== null) {
            $whereExpr->eq(TournamentTableMapper::FIELD_ID, $filter->getTournamentId(),
                TournamentTableMapper::TABLE_NAME);
        }
        if ($filter->getTeamId() !== null) {
            $whereExpr->eq(TournamentSeasonTeamTableMapper::FIELD_TEAM_ID, $filter->getTeamId(),
                TournamentSeasonTeamTableMapper::TABLE_NAME);

            $query->addJoin($this->mapper->joinTournamentSeasonTeam());
        }
        if ($filter->getStatus() !== null) {
            $whereExpr->eq(SeasonTableMapper::FIELD_ACTIVE, StatusDatabaseConverter::toValue($filter->getStatus()));
        }

        return $this->db->getQueryResults($query, [$this->mapper, 'toEntity']);
    }

    public function findById(string $id): ?ISeasonEntity
    {
        $query = $this->db->createQuery($this->mapper->getTableName())
            ->where($this->db->andExpression()->eq(SeasonTableMapper::FIELD_ID, $id))
            ->addJoin($this->mapper->joinTournament());
        return $this->db->getSingleResult($query, [$this->mapper, 'toEntity']);
    }

    /**
     * @param array $ids
     * @return ISeasonCollection
     */
    public function findByIds(array $ids): ISeasonCollection
    {
        if (!empty($ids)) {
            $query = $this->db->createQuery($this->mapper->getTableName())
                ->where($this->db->andExpression()->in(SeasonTableMapper::FIELD_ID, $ids))
                ->addJoin($this->mapper->joinTournament());
            $results = $this->db->getQueryResults($query, [$this->mapper, 'toEntity']);
            return new SeasonCollection($results);
        }
        return new SeasonCollection([]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function exists($id): bool
    {
        return $this->db->exists($this->mapper->getTableName(), [SeasonTableMapper::FIELD_ID => $id]);
    }

    public function insert($seasonEntity): ISeasonEntity
    {
        return $this->databaseUpdate->insertGeneratedId(SeasonTableMapper::TABLE_NAME, $seasonEntity);
    }

    public function update($seasonEntity): ?ISeasonEntity
    {
        if ($this->databaseUpdate->update(SeasonTableMapper::TABLE_NAME, $seasonEntity) === 0) {
            return null;
        }

        return $this->findById($seasonEntity->getId());
    }

    public function findByTournamentIdAndName($seasonEntity): array
    {
        $whereExpr = $this->db->andExpression();
        $whereExpr->eq(SeasonTableMapper::FIELD_TOURNAMENT_ID, $seasonEntity->getTournamentId());
        $whereExpr->eq(SeasonTableMapper::FIELD_NAME, $seasonEntity->getName());

        $query = $this->db->createQuery($this->mapper->getTableName())
            ->where($whereExpr);

        return $this->db->getQueryResults($query);
    }
}