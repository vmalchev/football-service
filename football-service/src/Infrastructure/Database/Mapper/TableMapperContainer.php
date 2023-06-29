<?php


namespace Sportal\FootballApi\Infrastructure\Database\Mapper;


use InvalidArgumentException;

class TableMapperContainer
{
    /**
     * @var TableMapper[]
     */
    private array $tableMappers = [];

    /**
     * RelationContainer constructor.
     * @param mixed $tableMappers
     */
    public function __construct($tableMappers)
    {
        foreach ($tableMappers as $mapper) {
            if (isset($this->tableMappers[$mapper->getTableName()])) {
                throw new InvalidArgumentException("Duplicate mapper registered for {$mapper->getTableName()}");
            }
            $this->tableMappers[$mapper->getTableName()] = $mapper;
        }
    }

    public function getFor(string $tableName): ?TableMapper
    {
        return $this->tableMappers[$tableName] ?? null;
    }
}