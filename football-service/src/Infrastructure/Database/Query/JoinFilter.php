<?php


namespace Sportal\FootballApi\Infrastructure\Database\Query;


use Doctrine\DBAL\Query\QueryBuilder;

class JoinFilter implements Expression
{
    private TableColumn $leftCondition;

    private TableColumn $rightCondition;

    /**
     * JoinFilter constructor.
     * @param TableColumn $leftCondition
     * @param TableColumn $rightCondition
     */
    public function __construct(TableColumn $leftCondition, TableColumn $rightCondition)
    {
        $this->leftCondition = $leftCondition;
        $this->rightCondition = $rightCondition;
    }

    /**
     * @inheritDoc
     */
    public function build(QueryBuilder $qb, callable $aliasResolver)
    {
        $aliasLeft = $aliasResolver($this->leftCondition->getTableName());
        $fieldLeft = $aliasLeft . "." . $this->leftCondition->getColumnName();

        $aliasRight = $aliasResolver($this->rightCondition->getTableName());
        $fieldRight = $aliasRight . "." . $this->rightCondition->getColumnName();

        return "$fieldLeft = $fieldRight";
    }
}