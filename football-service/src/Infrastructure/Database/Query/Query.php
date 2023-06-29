<?php

namespace Sportal\FootballApi\Infrastructure\Database\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Sportal\FootballApi\Infrastructure\Database\Relation\Relation;

final class Query
{
    const TABLE_ALIAS = "t0";

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

    private $fromName;

    private $fromColumns;

    private $joinAliasMap;

    private $orderBy;

    private $groupBy;

    private $maxResults;

    private $offset;

    private JoinBuilder $joinBuilder;

    private bool $hasDistinct = false;

    public function __construct(string $tableName, array $columns = null, JoinBuilder $joinBuilder)
    {
        $this->fromName = $tableName;
        $this->fromColumns = (!empty($columns)) ? $columns : [
            self::TABLE_ALIAS . ".*"
        ];
        $this->orderBy = [];
        $this->groupBy = [];
        $this->maxResults = null;
        $this->offset = null;
        $this->joinBuilder = $joinBuilder;
    }

    public function resolveAlias($joinId)
    {
        if (empty($joinId)) {
            return self::TABLE_ALIAS;
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

    public function addSelect(string $expression, ?string $alias = null)
    {
        if (isset($alias)) {
            $expression .= ' as ' . $alias;
        }

        $this->fromColumns[] = $expression;
        return $this;
    }

    public function addJoin(Join $join)
    {
        $this->joins[] = $join;
        return $this;
    }

    /**
     * @param Relation $relation
     * @return $this
     */
    public function addRelation(Relation $relation): Query
    {
        $this->joins[] = $this->joinBuilder->build($relation);
        return $this;
    }

    /**
     * @param Relation[] $relations
     * @return $this
     */
    public function addRelations(?array $relations): Query
    {
        if (!empty($relations)) {
            foreach ($relations as $relation) {
                $this->addRelation($relation);
            }
        }
        return $this;
    }

    public function addChildRelation(string $tableIdentifier, Relation $child): Query
    {
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $this->addChildRelationRecursively($tableIdentifier, $join, $child);
            }
        }
        return $this;
    }

    private function addChildRelationRecursively(string $tableIdentifier, Join $join, Relation $child): void
    {
        if ($join->hasChildren()) {
            foreach ($join->getChildren() as $join) {
                $this->addChildRelationRecursively($tableIdentifier, $join, $child);
            }
        }

        if ($tableIdentifier === $join->getIdentifier()) {
            $join->addChild($this->joinBuilder->build($child));
        }
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

    public function addOrderByAlias($alias, $order = null)
    {
        $this->orderBy[] = [
            'alias' => $alias,
            'order' => !empty($order) && strtolower($order) == 'desc' ? 'DESC' : 'ASC'
        ];

        return $this;
    }

    public function addGroupBy(string $tableName, string $columnName)
    {
        $this->groupBy[] = ['tableIdentifier' => $tableName, 'column' => $columnName];

        return $this;
    }

    public function build(QueryBuilder $qb)
    {
        $qb->select($this->fromColumns)->from($this->fromName, self::TABLE_ALIAS);
        $this->joinAliasMap = [];
        if ($this->hasJoin()) {
            $this->joinAliasMap[$this->fromName][] = self::TABLE_ALIAS;
            list ($joinedColumns) = $this->joinRecursive($qb, $this->joins, self::TABLE_ALIAS);
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

        if ($this->hasDistinct) {
            $qb->distinct();
        }

        $this->buildGroup($qb);

        $this->buildOrder($qb);


    }

    public function hasJoin()
    {
        return !empty($this->joins);
    }

    private function setMaxResults($number)
    {
        $this->maxResults = $number;
        return $this;
    }

    // Alias to maxResults
    public function limit($limit)
    {
        return $this->setMaxResults($limit);
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function clearOrderBy(): Query
    {
        $updated = clone $this;
        $updated->orderBy = [];
        return $updated;
    }

    public function distinct(): Query
    {
        $this->hasDistinct = true;
        return $this;
    }

    public function hasDistinct(): bool
    {
        return $this->hasDistinct;
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
            if ($join->getJoinCondition() !== null) {
                $condition = $join->getJoinCondition()->build($qb, [$this, 'resolveAlias']);
            } else {
                $condition = "$alias.{$join->getReference()} = $fromAlias.{$join->getForeignKey()}";
            }
            $tableName = $join->getTableName();
            $leftJoin = $join->getType() == Join::TYPE_LEFT || $forceLeftJoin;
            if ($leftJoin) {
                $qb->leftJoin($fromAlias, $tableName, $alias, $condition);
            } elseif ($join->getType() == Join::TYPE_INNER) {
                $qb->innerJoin($fromAlias, $tableName, $alias, $condition);
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
            $emptyColumns = [];
            foreach ($rel->getColumns() as $column) {
                $key = $alias . $column;
                if (isset($row[$key])) {
                    $joinData[$column] = $row[$key];
                } else {
                    $emptyColumns[$column] = null;
                }
                unset($row[$key]);
            }
            if ($rel->hasChildren()) {
                list ($row, $joinedColumns) = $this->expandRecursive($row, $rel->getChildren());
                $joinData = array_merge($joinData, $joinedColumns);
            }

            if (!empty($joinData)) {
                $factoryData = array_merge($emptyColumns, $joinData);
                if (!is_null($rel->getFactory())) {
                    $expandedJoin[$objName] = call_user_func($rel->getFactory(), $factoryData);
                } else {
                    $expandedJoin[$objName] = $factoryData;
                }
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
            $sort = $order['alias'] ?? $this->resolveAlias($order['joinId']) . "." . $order['key'];
            $qb->addOrderBy($sort, $order['order']);
        }
    }

    private function buildGroup(QueryBuilder $qb)
    {
        foreach ($this->groupBy as $group) {
            $qb->addGroupBy($this->resolveAlias($group['tableIdentifier']) . "." . $group['column']);
        }
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->maxResults;
    }

    /**
     * @return int|null
     */
    public function getOffset(): ?int
    {
        return $this->offset;
    }


}