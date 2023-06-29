<?php

namespace Sportal\FootballApi\Infrastructure\Database\Query;

class JoinFactory
{

    public function createInner($className, array $columns, $foreignKey = null, $objectName = null)
    {
        return (new Join($className, $columns, Join::TYPE_INNER))->setForeignKey($foreignKey)->setObjectName(
            $objectName);
    }

    /**
     * @param $className
     * @param array $columns
     * @param null $foreignKey
     * @param null $objectName
     * @return Join
     */
    public function createLeft($className, array $columns, $foreignKey = null, $objectName = null)
    {
        return (new Join($className, $columns, Join::TYPE_LEFT))->setForeignKey($foreignKey)->setObjectName($objectName);
    }
}