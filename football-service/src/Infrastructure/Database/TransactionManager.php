<?php


namespace Sportal\FootballApi\Infrastructure\Database;


use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Domain\Database\IDatabaseTransaction;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\Database\TransactionException;

class TransactionManager implements ITransactionManager
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * TransactionManager constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @inheritDoc
     */
    function start(): IDatabaseTransaction
    {
        if ($this->conn->getTransactionNestingLevel() === 0) {
            return new DatabaseTransaction($this->conn);
        } else {
            throw new TransactionException("Transaction started");
        }
    }

    function transactional(\Closure $func)
    {
        if ($this->conn->getTransactionNestingLevel() === 0) {
            return $this->conn->transactional(function () use ($func) {
                return $func(new DatabaseUpdate($this->conn));
            });
        } else {
            return $func(new DatabaseUpdate($this->conn));
        }
    }
}