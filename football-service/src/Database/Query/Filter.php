<?php

namespace Sportal\FootballApi\Database\Query;

use Doctrine\DBAL\Query\QueryBuilder;

class Filter implements Expression
{

    const TYPE_IN = 'in';

    const TYPE_EQ = '=';

    private $key;

    private $type;

    private $value;

    private $joinId;

    public function __construct($key, $value, $type = null, $joinId = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->type = (empty($type)) ? static::TYPE_IN : $type;
        $this->joinId = $joinId;
    }

    public function build(QueryBuilder $qb, callable $aliasResolver)
    {
        $alias = $aliasResolver($this->joinId);
        $field = $alias . "." . $this->key;
        if ($this->type == static::TYPE_IN) {
            $in = [];
            foreach ($this->value as $value) {
                $in[] = $qb->createPositionalParameter($value);
            }
            $part = $qb->expr()->in($field, $in);
        } else {
            if ($this->value === null) {
                $part = "{$field} is null";
            } else {
                $part = $field . " " . $this->type . " " . $qb->createPositionalParameter($this->value);
            }
        }
        return $part;
    }
}