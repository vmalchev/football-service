<?php


namespace Sportal\FootballApi\Infrastructure\Database;


use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Domain\Database\IDatabaseTransaction;

class DatabaseTransaction implements IDatabaseTransaction
{
    /**
     * @var Connection
     */
    private $conn;

    private $isActive;

    /**
     * DatabaseTransaction constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
        $this->conn->beginTransaction();
        $this->isActive = true;
    }

    public function __destruct()
    {
        $this->rollBack();
    }

    function commit(): bool
    {
        if ($this->isActive && $this->conn->commit()) {
            $this->isActive = false;
            return true;
        } else {
            return false;
        }
    }

    function rollBack(): bool
    {
        if ($this->isActive && $this->conn->rollBack()) {
            $this->isActive = false;
            return true;
        } else {
            return false;
        }
    }
}