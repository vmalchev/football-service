<?php


namespace Sportal\FootballApi\Infrastructure\MatchStatus;


use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;

class MatchStatusRepository implements IMatchStatusRepository
{

    private Database $db;

    private MatchStatusTableMapper $tableMapper;

    /**
     * StageRepository constructor.
     * @param Database $db
     * @param MatchStatusTableMapper $tableMapper
     */
    public function __construct(Database $db, MatchStatusTableMapper $tableMapper)
    {
        $this->db = $db;
        $this->tableMapper = $tableMapper;
    }


    /**
     * @inheritDoc
     */
    public function findById(string $id): ?IMatchStatusEntity
    {
        $expr = $this->db->andExpression()->eq(MatchStatusTableMapper::FIELD_ID, $id);
        $query = $this->db->createQuery(MatchStatusTableMapper::TABLE_NAME)
            ->where($expr);
        return $this->db->getSingleResult($query, [$this->tableMapper, 'toEntity']);
    }

    public function findByStatusTypes(array $types): ?array
    {
        $expr = $this->db->andExpression()->in(MatchStatusTableMapper::FIELD_TYPE, $types);
        $query = $this->db->createQuery(MatchStatusTableMapper::TABLE_NAME)
            ->where($expr);

        return $this->db->getQueryResults($query, [$this->tableMapper, 'toEntity']);
    }

    public function findByStatusCodes(array $codes): ?array
    {
        $expr = $this->db->andExpression()->in(MatchStatusTableMapper::FIELD_CODE, $codes);
        $query = $this->db->createQuery(MatchStatusTableMapper::TABLE_NAME)
            ->where($expr);

        return $this->db->getQueryResults($query, [$this->tableMapper, 'toEntity']);
    }

    public function findAll(): ?array
    {
        $query = $this->db->createQuery(MatchStatusTableMapper::TABLE_NAME);

        return $this->db->getQueryResults($query, [$this->tableMapper, 'toEntity']);
    }
}