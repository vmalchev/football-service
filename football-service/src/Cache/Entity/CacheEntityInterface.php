<?php
namespace Sportal\FootballApi\Cache\Entity;

interface CacheEntityInterface
{

    /**
     * @return string primary identifier of the entity
     */
    public function getPrimaryKeyMap();
}

