<?php

namespace Sportal\FootballApi\Database\Query;

use Doctrine\DBAL\Query\QueryBuilder;

class CompositeExpression implements Expression
{

    const TYPE_AND = 'AND';

    const TYPE_OR = 'OR';

    private $type;

    /**
     *
     * @var Expression[]
     */
    private $parts;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function build(QueryBuilder $qb, callable $aliasResolver)
    {
        $expr = ($this->type == static::TYPE_OR) ? $qb->expr()->orX() : $qb->expr()->andX();
        foreach ($this->parts as $part) {
            $part = $part->build($qb, $aliasResolver);
            $expr->add($part);
        }
        return $expr;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function eq($key, $value, $joinId = null)
    {
        $filter = new Filter($key, $value, Filter::TYPE_EQ, $joinId);
        $this->parts[] = $filter;
        return $this;
    }

    public function gteq($key, $value, $joinId = null)
    {
        $filter = new Filter($key, $value, '>=', $joinId);
        $this->parts[] = $filter;
        return $this;
    }

    public function lteq($key, $value, $joinId = null)
    {
        $filter = new Filter($key, $value, '<=', $joinId);
        $this->parts[] = $filter;
        return $this;
    }

    public function add(Expression $expr)
    {
        $this->parts[] = $expr;
        return $this;
    }

    public function in($key, array $values, $joinId = null)
    {
        $filter = new Filter($key, $values, Filter::TYPE_IN, $joinId);
        $this->parts[] = $filter;
        return $this;
    }
}