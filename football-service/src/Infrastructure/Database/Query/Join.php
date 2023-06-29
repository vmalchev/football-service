<?php

namespace Sportal\FootballApi\Infrastructure\Database\Query;

use Sportal\FootballApi\Database\SurrogateKeyInterface;
use Sportal\FootballApi\Util\NameUtil;

class Join
{

    const TYPE_LEFT = 'left';

    const TYPE_INNER = 'inner';

    private $className;

    private $columns;

    private $type;

    /**
     *
     * @var Join[]
     */
    private $joins;

    private $objectName;

    private $alias;

    private $foreignKey;

    private $reference;

    private $tableName;

    private $identifier;

    private $factory = null;

    private ?Expression $joinCondition = null;

    public function __construct($className, array $columns, $type = null)
    {
        $this->className = $className;
        $this->columns = $columns;
        $this->type = !empty($type) ? $type : static::TYPE_INNER;
        $this->setTableName(NameUtil::persistanceName($className));
    }

    public function getObjectName()
    {
        if (!empty($this->objectName)) {
            return $this->objectName;
        } elseif (!empty($this->tableName)) {
            return $this->tableName;
        }
        return null;
    }

    public function hasChildren()
    {
        return !empty($this->joins);
    }

    public function getChildren()
    {
        return $this->joins;
    }

    public function setChildren(array $joins)
    {
        $this->joins = $joins;
        return $this;
    }

    public function addChild(Join $join)
    {
        $this->joins[] = $join;
        return $this;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function getForeignKey()
    {
        if (!empty($this->foreignKey)) {
            return $this->foreignKey;
        } elseif (!empty($this->tableName)) {
            return $this->tableName . "_id";
        }
    }

    public function getReference()
    {
        if (!empty($this->reference)) {
            return $this->reference;
        } elseif (is_subclass_of($this->className, SurrogateKeyInterface::class)) {
            return 'id';
        }
        return 'id';
        /*
         * throw new \InvalidArgumentException(
         * $this->className . ' not a surrogate key model and no reference is specified');
         */
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;
        return $this;
    }

    public function setObjectName($objectName)
    {
        $this->objectName = $objectName;
        return $this;
    }

    public function getIdentifier()
    {
        if (!empty($this->identifier)) {
            return $this->identifier;
        }
        return $this->className;
    }


    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
    }

    public function setFactory(callable $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    public function getFactory(): ?callable
    {
        return $this->factory;
    }

    /**
     * @return Expression|null
     */
    public function getJoinCondition(): ?Expression
    {
        return $this->joinCondition;
    }

    /**
     * @param Expression|null $joinCondition
     * @return Join
     */
    public function setJoinCondition(?Expression $joinCondition): Join
    {
        $this->joinCondition = $joinCondition;
        return $this;
    }

}