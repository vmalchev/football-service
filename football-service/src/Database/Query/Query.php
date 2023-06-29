<?php

namespace Sportal\FootballApi\Database\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Sportal\FootballApi\Util\ModelNameUtil;

class Query
{

    /**
     *
     * @var Expression
     */
    private $where;

    /**
     *
     * @var Join[]
     */
    private $joins;

    private $nameUtil;

    private $fromName;

    private $fromColumns;

    private $joinAliasMap;

    private $orderBy;

    private $maxResults;

    private $offset;

    public function __construct(ModelNameUtil $nameUtil)
    {
        $this->nameUtil = $nameUtil;
        $this->orderBy = [];
        $this->maxResults = null;
        $this->offset = null;
    }

    public function resolveAlias($joinId)
    {
        if (empty($joinId)) {
            return 't0';
        } elseif (!empty($this->joinAliasMap[$joinId])) {
            if (!isset($this->joinAliasMap[$joinId][1])) {
                return $this->joinAliasMap[$joinId][0];
            } else {
                throw new \InvalidArgumentException(
                    "$joinId is ambigious, can't resolve table alias in Query. Use unique Join identifier");
            }
        }
        throw new \InvalidArgumentException($joinId . " is not found in Joined tables");
    }

    public function where(Expression $expr)
    {
        $this->where = $expr;
        return $this;
    }

    public function whereEquals($key, $value, $joinId = null)
    {
        return $this->where(new Filter($key, $value, Filter::TYPE_EQ, $joinId));
    }

    public function orX()
    {
        return new CompositeExpression(CompositeExpression::TYPE_OR);
    }

    /**
     *
     * @return \Sportal\FootballApi\Database\Query\CompositeExpression
     */
    public function andX()
    {
        return new CompositeExpression(CompositeExpression::TYPE_AND);
    }

    public function addJoin(Join $join)
    {
        $this->joins[] = $join;
        return $this;
    }

    public function addJoinList(array $joinList)
    {
        foreach ($joinList as $join) {
            $this->addJoin($join);
        }
        return $this;
    }

    public function from($name, array $columns = null)
    {
        $this->fromName = $this->nameUtil->persistanceName($name);
        $this->fromColumns = (!empty($columns)) ? $columns : [
            't0.*'
        ];
        return $this;
    }

    public function addOrderBy($key, $order = null, $joinId = null)
    {
        $this->orderBy[] = [
            'key' => $key,
            'order' => !empty($order) && strtolower($order) == 'desc' ? 'DESC' : 'ASC',
            'joinId' => $joinId
        ];

        return $this;
    }

    public function addOrderByNullsLast($key, $order = null, $joinId = null)
    {
        $this->orderBy[] = [
            'key' => $key,
            'order' => !empty($order) && strtolower($order) == 'desc' ? 'DESC NULLS LAST' : 'ASC',
            'joinId' => $joinId
        ];

        return $this;
    }


    public function build(QueryBuilder $qb)
    {
        $qb->select($this->fromColumns)->from($this->fromName, 't0');
        $this->joinAliasMap = [];
        if ($this->hasJoin()) {
            list ($joinedColumns) = $this->joinRecursive($qb, $this->joins, 't0');
            $qb->addSelect($joinedColumns);
        }

        if ($this->where !== null) {
            $expr = $this->where->build($qb, [
                $this,
                'resolveAlias'
            ]);
            $qb->where($expr);
        }

        if ($this->maxResults !== null) {
            $qb->setMaxResults($this->maxResults);
        }

        if ($this->offset) {
            $qb->setFirstResult($this->offset);
        }

        $this->buildOrder($qb);


    }

    public function hasJoin()
    {
        return !empty($this->joins);
    }

    public function setMaxResults($number)
    {
        $this->maxResults = $number;
        return $this;
    }

    // Alias to maxResults
    public function limit($offset)
    {
        return $this->setMaxResults($offset);
    }

    public function offset($offset)
    {
        if ($offset > 0) {
            $this->offset = $offset;
        }
        return $this;
    }

    public function expandRow(array $row)
    {
        list ($row, $expandedJoins) = $this->expandRecursive($row, $this->joins);
        $result = array_merge($row, $expandedJoins);
        return $result;
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param Join[] $joins
     * @param string $fromAlias
     * @param integer $tableId
     * @param string $forceLeftJoin
     */
    private function joinRecursive(QueryBuilder $qb, array $joins, $fromAlias, $tableId = 1, $forceLeftJoin = false)
    {
        $columns = [];
        foreach ($joins as $join) {
            $alias = 't' . $tableId;
            $this->joinAliasMap[$join->getIdentifier()][] = $alias;
            $join->setAlias($alias);
            $tableName = $join->getTableName();
            $foreignKey = $join->getForeignKey();
            $refkey = $join->getReference();
            $leftJoin = $join->getType() == Join::TYPE_LEFT || $forceLeftJoin;
            if ($leftJoin) {
                $qb->leftJoin($fromAlias, $tableName, $alias, "$alias.$refkey = $fromAlias." . $foreignKey);
            } elseif ($join->getType() == Join::TYPE_INNER) {
                $qb->innerJoin($fromAlias, $tableName, $alias, "$alias.$refkey = $fromAlias." . $foreignKey);
            }
            foreach ($join->getColumns() as $column) {
                $columns[] = $alias . "." . $column . " as " . $alias . "_" . $column;
            }
            if ($join->hasChildren()) {
                list ($addedColumns, $tableId) = $this->joinRecursive($qb, $join->getChildren(), $alias, $tableId + 1,
                    $leftJoin);
                foreach ($addedColumns as $column) {
                    $columns[] = $column;
                }
            } else {
                $tableId++;
            }
        }
        return [
            $columns,
            $tableId
        ];
    }

    /**
     *
     * @param array $row
     * @param Join[] $joins
     * @return array
     */
    private function expandRecursive(array $row, array $joins)
    {
        $expandedJoin = [];
        foreach ($joins as $rel) {
            $alias = $rel->getAlias() . "_";
            $objName = $rel->getObjectName();
            $joinData = [];
            foreach ($rel->getColumns() as $column) {
                $key = $alias . $column;
                if (isset($row[$key])) {
                    $joinData[$column] = $row[$key];
                }
                unset($row[$key]);
            }
            if ($rel->hasChildren()) {
                list ($row, $joinedColumns) = $this->expandRecursive($row, $rel->getChildren());
                $joinData = array_merge($joinData, $joinedColumns);
            }

            if (!empty($joinData)) {
                $expandedJoin[$objName] = $joinData;
            }
        }

        return [
            $row,
            $expandedJoin
        ];
    }

    private function buildOrder(QueryBuilder $qb)
    {
        foreach ($this->orderBy as $order) {
            $sort = $this->resolveAlias($order['joinId']) . "." . $order['key'];
            $qb->addOrderBy($sort, $order['order']);
        }
    }
}