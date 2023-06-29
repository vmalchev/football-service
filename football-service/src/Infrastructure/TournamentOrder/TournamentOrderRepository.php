<?php

namespace Sportal\FootballApi\Infrastructure\TournamentOrder;

use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;

class TournamentOrderRepository implements ITournamentOrderRepository
{

    private Database $database;

    private DatabaseUpdate $databaseUpdate;

    private TournamentOrderTableMapper $tournamentOrderTableMapper;

    public function __construct(DatabaseUpdate $databaseUpdate,
                                TournamentOrderTableMapper $tournamentOrderTableMapper,
                                Database $database)
    {
        $this->databaseUpdate = $databaseUpdate;
        $this->tournamentOrderTableMapper = $tournamentOrderTableMapper;
        $this->database = $database;
    }

    public function insert(ITournamentOrderEntity $entity)
    {
        $this->databaseUpdate->insert($this->tournamentOrderTableMapper->getTableName(), $entity);
    }

    public function deleteByClientCode(string $clientCode): int
    {
        return $this->databaseUpdate->delete($this->tournamentOrderTableMapper->getTableName(), [
            TournamentOrderTableMapper::FIELD_CLIENT_CODE => $clientCode
        ]);
    }

    public function findByClientCode(string $clientCode): array
    {
        $query = $this->database
            ->createQuery($this->tournamentOrderTableMapper->getTableName())
            ->where($this->database->andExpression()->in(TournamentOrderTableMapper::FIELD_CLIENT_CODE, [$clientCode]))
            ->addRelations($this->tournamentOrderTableMapper->getRelations())
            ->addOrderBy(TournamentOrderTableMapper::FIELD_SORTORDER, SortDirection::ASC);

        return $this->database->getQueryResults($query, [$this->tournamentOrderTableMapper, 'toEntity']);
    }
}