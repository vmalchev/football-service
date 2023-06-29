<?php


namespace Sportal\FootballApi\Domain\Database;


interface IDatabaseTransaction
{
    function commit(): bool;

    function rollBack(): bool;
}