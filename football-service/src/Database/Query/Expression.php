<?php
namespace Sportal\FootballApi\Database\Query;

use Doctrine\DBAL\Query\QueryBuilder;

interface Expression
{

    /**
     *
     * @param QueryBuilder $qb
     * @param callable $aliasResolver
     */
    public function build(QueryBuilder $qb, callable $aliasResolver);
}