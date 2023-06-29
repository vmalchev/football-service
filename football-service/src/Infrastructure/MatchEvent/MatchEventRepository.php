<?php


namespace Sportal\FootballApi\Infrastructure\MatchEvent;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntity;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;

class MatchEventRepository implements IMatchEventRepository
{
    /**
     * @var Database
     */
    private Database $db;

    private DatabaseUpdate $dbUpdate;

    /**
     * @var MatchEventTableMapper
     */
    private MatchEventTableMapper $mapper;

    /**
     * @param Database $db
     * @param MatchEventTableMapper $tableMapper
     * @param DatabaseUpdate $dbUpdate
     */
    public function __construct(
        Database $db,
        MatchEventTableMapper $tableMapper,
        DatabaseUpdate $dbUpdate
    )
    {
        $this->db = $db;
        $this->mapper = $tableMapper;

        $this->dbUpdate = $dbUpdate;
    }

    public function findByMatchId(string $id): array
    {
        $query = $this->db
            ->createQuery($this->mapper->getTableName())
            ->where(
                $this->db->andExpression()
                    ->eq(MatchEventTableMapper::FIELD_EVENT_ID, $id)
                    ->eq(MatchEventTableMapper::FIELD_DELETED, 0)
            )->addRelations($this->mapper->getRelations())
            ->addOrderBy(MatchEventTableMapper::FIELD_MINUTE, SortDirection::ASC)
            ->addOrderBy(MatchEventTableMapper::FIELD_SORT_ORDER, SortDirection::ASC);

        return $this->db->getQueryResults($query, [$this->mapper, 'toEntity']);
    }

    public function deleteByMatch(IMatchEntity $matchEntity): void
    {
        $this->dbUpdate->updateMany(MatchEventTableMapper::TABLE_NAME,
            [MatchEventTableMapper::FIELD_DELETED => true],
            [MatchEventTableMapper::FIELD_EVENT_ID => $matchEntity->getId()]);
    }

    public function update(IMatchEventEntity $matchEventEntity): void
    {
        if (!$matchEventEntity instanceof MatchEventEntity) {
            throw new InvalidArgumentException("Entity must be an instance of" . MatchEventEntity::class);
        }
        $this->dbUpdate->update(MatchEventTableMapper::TABLE_NAME, $matchEventEntity);
    }

    public function insert(IMatchEventEntity $matchEventEntity): IMatchEventEntity
    {
        if (!$matchEventEntity instanceof MatchEventEntity) {
            throw new InvalidArgumentException("Entity must be an instance of" . MatchEventEntity::class);
        }
        return $this->dbUpdate->insertGeneratedId(MatchEventTableMapper::TABLE_NAME, $matchEventEntity);
    }
}