<?php


namespace Sportal\FootballApi\Infrastructure\Tournament;


use Sportal\FootballApi\Domain\Tournament\ITournamentRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;

class TournamentRepository implements ITournamentRepository
{
    /**
     * @var Database
     */
    private Database $database;

    private TournamentTableMapper $mapper;

    public function __construct(Database $database,
                                TournamentTableMapper $mapper)
    {
        $this->database = $database;
        $this->mapper = $mapper;
    }

    public function exists(string $id): bool
    {
        return $this->database->exists(TournamentTableMapper::TABLE_NAME, [TournamentTableMapper::FIELD_ID => $id]);
    }

    public function findById($id): ?TournamentEntity
    {
        $query = $this->database->createQuery($this->mapper->getTableName())
            ->where($this->database->andExpression()->eq(TournamentTableMapper::FIELD_ID, $id))
            ->addRelations($this->mapper->getRelations());

        return $this->database->getSingleResult($query, [$this->mapper, 'toEntity']);
    }

}