<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroupSelection;

use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionEntity;
use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class TournamentGroupSelectionRepository implements ITournamentGroupSelectionRepository
{

    private DatabaseUpdate $databaseUpdate;

    private Database $database;

    private TournamentGroupSelectionTableMapper $tableMapper;

    public function __construct(DatabaseUpdate $databaseUpdate,
                                Database $database,
                                TournamentGroupSelectionTableMapper $tableMapper)
    {
        $this->databaseUpdate = $databaseUpdate;
        $this->database = $database;
        $this->tableMapper = $tableMapper;
    }

    /**
     * @inheritDoc
     */
    public function insert(ITournamentGroupSelectionEntity $entity): int
    {
        return $this->databaseUpdate->insert(TournamentGroupSelectionTableMapper::TABLE_NAME, $entity);
    }

    /**
     * @inheritDoc
     */
    public function deleteByCodeAndDate(string $code, \DateTimeInterface $date): void
    {
        $this->databaseUpdate->delete(TournamentGroupSelectionTableMapper::TABLE_NAME, [
            TournamentGroupSelectionTableMapper::FIELD_CODE => $code,
            TournamentGroupSelectionTableMapper::FIELD_DATE => $date->format('Y-m-d')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function findByCodeAndDate(string $code, \DateTimeInterface $date): array
    {
        $query = $this->database->createQuery(TournamentGroupSelectionTableMapper::TABLE_NAME)
            ->where($this->database->andExpression()
                ->eq(TournamentGroupSelectionTableMapper::FIELD_CODE, $code)
                ->eq(TournamentGroupSelectionTableMapper::FIELD_DATE, $date->format('Y-m-d')));

        return $this->database->getQueryResults($query, [$this->tableMapper, 'toEntity']);
    }

}