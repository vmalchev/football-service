<?php


namespace Sportal\FootballApi\Domain\Database;


interface ITransactionManager
{
    /**
     * Execute the specified closure in a transaction, handling rollback on exception
     * @param \Closure $func
     * @return mixed result of closure on success
     */
    function transactional(\Closure $func);

    /**
     * @return IDatabaseTransaction object to control started transaction
     * @throws TransactionException if a transaction is already active
     */
    function start(): IDatabaseTransaction;
}