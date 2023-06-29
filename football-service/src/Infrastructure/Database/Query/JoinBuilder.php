<?php


namespace Sportal\FootballApi\Infrastructure\Database\Query;


use Closure;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapperContainer;
use Sportal\FootballApi\Infrastructure\Database\Relation\Relation;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;

class JoinBuilder
{
    private TableMapperContainer $container;
    private RelationFactory $relationFactory;

    /**
     * RelationToJoinConverter constructor.
     * @param TableMapperContainer $container
     * @param RelationFactory $relationFactory
     */
    public function __construct(TableMapperContainer $container, RelationFactory $relationFactory)
    {
        $this->container = $container;
        $this->relationFactory = $relationFactory;
    }


    public function build(Relation $relation): Join
    {
        $tableMapper = $this->container->getFor($relation->getTableName());

        $mergedRelationFactory = $this->relationFactory->from($relation->getTableName(), $relation->getType())
            ->setForeignKey($relation->getForeignKey())
            ->setObjectKey($relation->getObjectKey())
            ->setJoinCondition($relation->getJoinCondition())
            ->setAliasName(($relation->getAliasName()));

        if ($tableMapper !== null) {
            $mergedRelationFactory->setColumns($tableMapper->getColumns())
                ->setChildren($tableMapper->getRelations())
                ->setObjectFactory(Closure::fromCallable([$tableMapper, 'toEntity']));
        }

        if ($relation->getColumns() !== null) {
            $mergedRelationFactory->setColumns($relation->getColumns());
        }
        if ($relation->getChildren() !== null) {
            $mergedRelationFactory->setChildren($relation->getChildren());
        }
        if ($relation->getObjectFactory() !== null) {
            $mergedRelationFactory->setObjectFactory($relation->getObjectFactory());
        }

        $mergedRelation = $mergedRelationFactory->create();

        $join = (new Join($mergedRelation->getTableName(), $mergedRelation->getColumns(), $mergedRelation->getType()->getValue()))
            ->setJoinCondition($mergedRelation->getJoinCondition())
            ->setForeignKey($mergedRelation->getForeignKey())
            ->setObjectName($mergedRelation->getObjectKey())
            ->setIdentifier($mergedRelation->getAliasName());
        if ($mergedRelation->getObjectFactory() !== null) {
            $join->setFactory($mergedRelation->getObjectFactory());
        }
        if (!empty($mergedRelation->getChildren())) {
            $children = $mergedRelation->getChildren();
            foreach ($children as $child) {
                $join->addChild($this->build($child));
            }
        }
        return $join;
    }
}