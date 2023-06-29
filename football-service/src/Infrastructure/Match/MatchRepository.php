<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Match\MatchExpressionBuilder;
use Sportal\FootballApi\Domain\Match\MatchFilter;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFilter;
use Sportal\FootballApi\Infrastructure\Database\Query\TableColumn;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Tournament\TournamentTableMapper;
use Sportal\FootballApi\Infrastructure\TournamentOrder\TournamentOrderTableMapper;

class MatchRepository implements IMatchRepository
{
    private Database $db;

    private MatchTableMapper $tableMapper;

    private RelationFactory $relationFactory;

    private DatabaseUpdate $dbUpdate;

    private MatchExpressionBuilder $matchExpressionBuilder;

    /**
     * MatchRepository constructor.
     * @param Database $db
     * @param MatchTableMapper $tableMapper
     * @param RelationFactory $relationFactory
     * @param DatabaseUpdate $dbUpdate
     * @param MatchExpressionBuilder $matchExpressionBuilder
     */
    public function __construct(
        Database               $db,
        MatchTableMapper       $tableMapper,
        RelationFactory $relationFactory,
        DatabaseUpdate         $dbUpdate,
        MatchExpressionBuilder $matchExpressionBuilder)
    {
        $this->db = $db;
        $this->tableMapper = $tableMapper;
        $this->relationFactory = $relationFactory;
        $this->dbUpdate = $dbUpdate;
        $this->matchExpressionBuilder = $matchExpressionBuilder;
    }

    /**
     * @inheritDoc
     */
    public function findById(string $id): ?IMatchEntity
    {
        $expr = $this->db->andExpression()->eq(MatchTableMapper::FIELD_ID, $id);
        $query = $this->db->createQuery(MatchTableMapper::TABLE_NAME)
            ->where($expr)
            ->addRelations($this->tableMapper->getRelations());
        return $this->db->getSingleResult($query, [$this->tableMapper, 'toEntity']);
    }

    /**
     * @inheritDoc
     */
    public function existsById(string $id): bool
    {
        return $this->db->exists(MatchTableMapper::TABLE_NAME, [MatchTableMapper::FIELD_ID => $id]);
    }

    public function insert(IMatchEntity $matchEntity): IMatchEntity
    {
        if ($matchEntity instanceof MatchEntity) {
            return $this->dbUpdate->insertGeneratedId($this->tableMapper->getTableName(), $matchEntity);
        }
        throw new InvalidArgumentException("entity must be instance of " . MatchEntity::class);
    }

    public function update(IMatchEntity $matchEntity): void
    {
        $this->dbUpdate->update($this->tableMapper->getTableName(), $matchEntity);
    }

    /**
     * @inheritDoc
     */
    public function findByFilter(MatchFilter $filter): array
    {
        $query = $this->db->createQuery(MatchTableMapper::TABLE_NAME)
            ->where($this->matchExpressionBuilder->build($filter))
            ->addRelations($this->tableMapper->getRelations());

        if (!is_null($filter->getTournamentGroup())) {

            $tournamentSortRelation = $this->relationFactory->from(TournamentOrderTableMapper::TABLE_NAME, RelationType::OPTIONAL())
                ->withoutChildren()
                ->setJoinCondition($this->db->andExpression()
                ->add(
                    new JoinFilter(
                        new TableColumn(TournamentOrderTableMapper::TABLE_NAME, TournamentOrderTableMapper::FIELD_TOURNAMENT_ID),
                        new TableColumn(TournamentTableMapper::TABLE_NAME, TournamentTableMapper::FIELD_ID)))
                ->eq(TournamentOrderTableMapper::FIELD_CLIENT_CODE, $filter->getTournamentGroup(), TournamentOrderTableMapper::TABLE_NAME))
                ->create();

            $query
                ->addChildRelation(TournamentTableMapper::TABLE_NAME, $tournamentSortRelation)
                ->addOrderBy(TournamentOrderTableMapper::FIELD_SORTORDER, SORT_ASC, TournamentOrderTableMapper::TABLE_NAME)
                ->addOrderBy(MatchTableMapper::FIELD_KICKOFF_TIME, SORT_ASC)
                ->addOrderBy(MatchTableMapper::FIELD_ID, SORT_ASC);
        } else {
            $query->addOrderBy(MatchTableMapper::FIELD_KICKOFF_TIME, $filter->getSortDirection());
            $query->addOrderBy(MatchTableMapper::FIELD_STAGE_ID, $filter->getSortDirection());
            $query->addOrderBy(MatchTableMapper::FIELD_ID, $filter->getSortDirection());
        }

        return $this->db->getPagedQueryResults($query, [$this->tableMapper, 'toEntity'])->getData();
    }
}