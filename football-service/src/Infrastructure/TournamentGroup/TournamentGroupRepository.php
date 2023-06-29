<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroup;

use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntity;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class TournamentGroupRepository implements ITournamentGroupRepository
{

    private Database $database;

    private DatabaseUpdate $databaseUpdate;

    private TournamentGroupTableMapper $tournamentGroupTableMapper;

    public function __construct(Database $database,
                                TournamentGroupTableMapper $tournamentGroupTableMapper,
                                DatabaseUpdate $databaseUpdate)
    {
        $this->database = $database;
        $this->tournamentGroupTableMapper = $tournamentGroupTableMapper;
        $this->databaseUpdate = $databaseUpdate;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        $query = $this->database->createQuery($this->tournamentGroupTableMapper->getTableName());
        return $this->database->getQueryResults($query, [$this->tournamentGroupTableMapper, 'toEntity']);
    }

    /**
     * @inheritDoc
     */
    public function findByCode(string $code): ?ITournamentGroupEntity
    {
        $query = $this->database->createQuery($this->tournamentGroupTableMapper->getTableName());
        $query->where($this->database->andExpression()->eq(TournamentGroupTableMapper::FIELD_CODE, $code));

        return $this->database->getSingleResult($query, [$this->tournamentGroupTableMapper, 'toEntity']);
    }

    /**
     * @inheritDoc
     */
    public function insert(ITournamentGroupEntity $entity): int
    {
        return $this->databaseUpdate->insert($this->tournamentGroupTableMapper->getTableName(), $entity);
    }

    /**
     * @inheritDoc
     */
    public function update(ITournamentGroupEntity $entity): int
    {
        return $this->databaseUpdate->update($this->tournamentGroupTableMapper->getTableName(), $entity);
    }

    /**
     * @inheritDoc
     */
    public function existsByCode(string $code): bool
    {
        return $this->database->exists($this->tournamentGroupTableMapper->getTableName(), [TournamentGroupTableMapper::FIELD_CODE => $code]);
    }

}