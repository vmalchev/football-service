<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\Team\ITeamCollection;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Team\ITeamRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFilter;
use Sportal\FootballApi\Infrastructure\Database\Query\Query;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Database\Query\TableColumn;
use Sportal\FootballApi\Infrastructure\Match\TeamColorsTableMapper;

class TeamRepository implements ITeamRepository
{
    /**
     * @var Database
     */
    private Database $db;

    /**
     * @var DatabaseUpdate
     */
    private DatabaseUpdate $dbUpdate;

    /**
     * @var TeamColorsTableMapper
     */
    private TeamColorsTableMapper $colorsMapper;

    /**
     * @var TeamTableMapper
     */
    private TeamTableMapper $tableMapper;

    /**
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param TeamColorsTableMapper $colorsMapper
     * @param TeamTableMapper $tableMapper
     */
    public function __construct(
        Database $db,
        DatabaseUpdate $dbUpdate,
        TeamColorsTableMapper $colorsMapper,
        TeamTableMapper $tableMapper
    )
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->colorsMapper = $colorsMapper;
        $this->tableMapper = $tableMapper;
    }

    public function findAll(): array
    {

        $query = $this->db
            ->createQuery(TeamTable::TABLE_NAME)
            ->addOrderBy(TeamTable::FIELD_CREATED_AT, SortDirection::DESC)
            ->addOrderBy(TeamTable::FIELD_ID, SortDirection::DESC)
            ->addRelations($this->tableMapper->getRelations());

        return $this->db->getPagedQueryResults(
            $query, [
                $this->tableMapper,
                "toEntity"
            ]
        )->getData();
    }

    public function insert(ITeamEntity $teamEditEntity): ITeamEntity
    {
        $this->dbUpdate->insertGeneratedId(TeamTable::TABLE_NAME, $teamEditEntity);
        return $this->findById($teamEditEntity->getId());
    }

    public function update(ITeamEntity $teamEditEntity): ITeamEntity
    {
        $this->dbUpdate->update(TeamTable::TABLE_NAME, $teamEditEntity);
        return $this->findById($teamEditEntity->getId());
    }

    public function findById(string $id): ?ITeamEntity
    {
        $whereExpression = $this->db->andExpression()->eq('id', $id);

        $query = $this->getQuery($whereExpression);

        return $this->db->getSingleResult($query, [$this->tableMapper, "toEntity"]);
    }

    /**
     * @param array $ids
     * @return ITeamCollection
     */
    public function findByIds(array $ids): ITeamCollection
    {
        if (empty($ids)) {
            return new TeamCollection([]);
        }

        $whereExpression = $this->db->andExpression()->in('id', $ids);

        $query = $this->getQuery($whereExpression);

        $result = $this->db->getQueryResults($query, [$this->tableMapper, "toEntity"]);
        return new TeamCollection($result);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool
    {
        return $this->db->exists(TeamTable::TABLE_NAME, [TeamTable::FIELD_ID => $id]);
    }

    private function getQuery($expression): Query
    {

        $colorsJoin = $this->db->getJoinFactory()
            ->createLeft(TeamColorsTable::TABLE_NAME, TeamColorsTable::getColumns())
            ->setJoinCondition($this->db->andExpression()->eq(TeamColorsTable::FIELD_ENTITY_TYPE, 'team', TeamColorsTable::TABLE_NAME)
                ->add(new JoinFilter(new TableColumn(TeamTable::TABLE_NAME, TeamTable::FIELD_ID),
                    new TableColumn(TeamColorsTable::TABLE_NAME, TeamColorsTable::FIELD_ENTITY_ID))))
            ->setFactory([$this->colorsMapper, 'toEntity']);


        $query = $this->db
            ->createQuery(TeamTable::TABLE_NAME)
            ->where($expression)
            ->addRelations($this->tableMapper->getRelations())
            ->addJoin($colorsJoin);

        return $query;
    }
}