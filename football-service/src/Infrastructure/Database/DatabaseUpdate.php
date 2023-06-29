<?php


namespace Sportal\FootballApi\Infrastructure\Database;


use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateTimeConverter;

class DatabaseUpdate
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * SingleUpdate constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function updateMany(string $tableName, array $update, array $identifier): int
    {
        return $this->conn->update($tableName, $this->convertEntry($update), $identifier);
    }

    public function insert(string $tableName, IDatabaseEntity $entity): int
    {
        return $this->conn->insert($tableName, $this->convertEntry($entity->getDatabaseEntry()));
    }

    public function insertGeneratedId(string $tableName, GeneratedIdDatabaseEntity $entity)
    {
        $affected = $this->insert($tableName, $entity);
        if ($affected === 1) {
            $id = $this->conn->lastInsertId($tableName . "_id_seq");
            return $entity->withId($id);
        }
        return $entity;
    }

    public function update(string $tableName, IDatabaseEntity $entity): int
    {
        return $this->conn->update($tableName, $this->convertEntry($entity->getDatabaseEntry()), $entity->getPrimaryKey());
    }

    public function delete(string $tableName, array $identifier): int
    {
        return $this->conn->delete($tableName, $identifier);
    }

    private function convertEntry(array $entry): array
    {
        $convertedEntry = [];
        foreach ($entry as $key => $value) {
            if ($value instanceof DateTimeInterface) {
                $convertedEntry[$key] = DateTimeConverter::toValue($value);
            } else if (is_bool($value)) {
                $convertedEntry[$key] = $value ? 1 : 0;
            } else {
                $convertedEntry[$key] = $value;
            }
        }
        return $convertedEntry;
    }
}