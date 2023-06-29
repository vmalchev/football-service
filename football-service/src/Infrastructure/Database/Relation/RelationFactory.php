<?php


namespace Sportal\FootballApi\Infrastructure\Database\Relation;


use Closure;
use Sportal\FootballApi\Infrastructure\Database\Query\Expression;

class RelationFactory
{
    private string $tableName;

    private RelationType $type;

    /**
     * @var string[]|null
     */
    private ?array $columns = null;

    /**
     * @var Relation[]|null
     */
    private ?array $children = null;

    private ?Closure $objectFactory = null;

    private ?string $foreignKey = null;

    private ?string $objectKey = null;

    private ?Expression $joinCondition = null;

    private ?string $aliasName = null;

    /**
     * @param Expression|null $joinCondition
     * @return RelationFactory
     */
    public function setJoinCondition(?Expression $joinCondition): RelationFactory
    {
        $this->joinCondition = $joinCondition;
        return $this;
    }

    /**
     * @param string $tableName
     * @param RelationType $type
     * @return RelationFactory
     */
    public function from(string $tableName, RelationType $type): RelationFactory
    {
        $factory = clone $this;
        $factory->tableName = $tableName;
        $factory->type = $type;
        return $factory;
    }

    /**
     * @param array $columns
     * @return RelationFactory
     */
    public function setColumns(array $columns): RelationFactory
    {
        $this->columns = $columns;
        return $this;
    }

    public function withoutChildren(): RelationFactory
    {
        $this->children = [];
        return $this;
    }


    public function create(): Relation
    {
        return new Relation($this->tableName,
            $this->type,
            $this->columns,
            $this->objectFactory,
            $this->children,
            $this->foreignKey,
            $this->objectKey,
            $this->joinCondition,
            $this->aliasName);
    }

    /**
     * @param Relation[]|null $children
     * @return RelationFactory
     */
    public function setChildren(?array $children): RelationFactory
    {
        $this->children = $children;
        return $this;
    }

    public function addChild(Relation $relation): RelationFactory
    {
        if ($this->children === null) {
            $this->children = [];
        }
        $this->children[] = $relation;
        return $this;
    }

    /**
     * @param Closure|null $objectFactory
     * @return RelationFactory
     */
    public function setObjectFactory(?Closure $objectFactory): RelationFactory
    {
        $this->objectFactory = $objectFactory;
        return $this;
    }

    /**
     * @param string|null $foreignKey
     * @return RelationFactory
     */
    public function setForeignKey(?string $foreignKey): RelationFactory
    {
        $this->foreignKey = $foreignKey;
        return $this;
    }

    /**
     * Overrides the default key in the associative array where the data from the relation will be added (after fetching query results)
     * @param string|null $objectKey
     * @return RelationFactory
     */
    public function setObjectKey(?string $objectKey): RelationFactory
    {
        $this->objectKey = $objectKey;
        return $this;
    }

    /**
     * @param string|null $aliasName
     * @return RelationFactory
     */
    public function setAliasName(?string $aliasName): RelationFactory
    {
        $this->aliasName = $aliasName;
        return $this;
    }
}