<?php


namespace Sportal\FootballApi\Infrastructure\LineupPlayerType;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeCollection;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;

class LineupPlayerTypeCollection implements ILineupPlayerTypeCollection
{
    /**
     * @var ILineupPlayerTypeEntity[]
     */
    private array $entities;

    /**
     * PlayerCollection constructor.
     * @param ILineupPlayerTypeEntity[] $entities
     */
    public function __construct(array $entities)
    {
        $this->entities = [];
        foreach ($entities as $entity) {
            $this->entities[$entity->getId()] = $entity;
        }
    }

    public function getAll(): array
    {
        return $this->entities;
    }

    public function getById(string $id): ?ILineupPlayerTypeEntity
    {
        return $this->entities[$id] ?? null;
    }
}