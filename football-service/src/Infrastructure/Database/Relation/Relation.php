<?php


namespace Sportal\FootballApi\Infrastructure\Database\Relation;


use Closure;
use Sportal\FootballApi\Infrastructure\Database\Query\Expression;

class Relation
{
    private string $tableName;
    private RelationType $type;
    private ?array $columns;
    private ?Closure $objectFactory;
    private ?array $children;
    private ?string $foreignKey;
    private ?string $objectKey;
    private ?Expression $joinCondition;
    private ?string $aliasName;

    /**
     * Relation constructor.
     * @param string $tableName
     * @param RelationType $type
     * @param array|null $columns
     * @param Closure|null $objectFactory
     * @param array|null $children
     * @param string|null $foreignKey
     * @param string|null $objectKey
     * @param Expression|null $joinCondition
     * @param string|null $aliasName
     */
    public function __construct(string $tableName,
                                RelationType $type,
                                ?array $columns,
                                ?Closure $objectFactory,
                                ?array $children,
                                ?string $foreignKey,
                                ?string $objectKey,
                                ?Expression $joinCondition,
                                ?string $aliasName)
    {
        $this->tableName = $tableName;
        $this->type = $type;
        $this->columns = $columns;
        $this->objectFactory = $objectFactory;
        $this->children = $children;
        $this->foreignKey = $foreignKey;
        $this->objectKey = $objectKey;
        $this->joinCondition = $joinCondition;
        $this->aliasName = $aliasName;
    }


    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return RelationType
     */
    public function getType(): RelationType
    {
        return $this->type;
    }

    /**
     * @return array|null
     */
    public function getColumns(): ?array
    {
        return $this->columns;
    }

    /**
     * @return Closure|null
     */
    public function getObjectFactory(): ?Closure
    {
        return $this->objectFactory;
    }

    /**
     * @return array|null
     */
    public function getChildren(): ?array
    {
        return $this->children;
    }

    /**
     * @return string|null
     */
    public function getForeignKey(): ?string
    {
        return $this->foreignKey;
    }

    /**
     * @return string|null
     */
    public function getObjectKey(): ?string
    {
        return $this->objectKey;
    }

    /**
     * @return Expression|null
     */
    public function getJoinCondition(): ?Expression
    {
        return $this->joinCondition;
    }

    /**
     * @return string|null
     */
    public function getAliasName(): ?string
    {
        return $this->aliasName;
    }

}