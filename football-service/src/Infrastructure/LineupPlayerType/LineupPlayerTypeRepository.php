<?php


namespace Sportal\FootballApi\Infrastructure\LineupPlayerType;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeCollection;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;

class LineupPlayerTypeRepository implements ILineupPlayerTypeRepository
{
    private Database $db;
    private LineupPlayerTypeTableMapper $tableMapper;

    /**
     * LineupPlayerTypeRepository constructor.
     * @param Database $db
     * @param LineupPlayerTypeTableMapper $tableMapper
     */
    public function __construct(Database $db, LineupPlayerTypeTableMapper $tableMapper)
    {
        $this->db = $db;
        $this->tableMapper = $tableMapper;
    }

    public function findAll(): ILineupPlayerTypeCollection
    {
        $query = $this->db->createQuery($this->tableMapper->getTableName());
        return new LineupPlayerTypeCollection($this->db->getQueryResults($query, [$this->tableMapper, 'create']));
    }
}