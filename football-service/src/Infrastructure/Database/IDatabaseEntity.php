<?php

namespace Sportal\FootballApi\Infrastructure\Database;

interface IDatabaseEntity
{

    /**
     * Returns a map of the entity as it should be stored in the database.
     * @return array key => value pairs
     */
    public function getDatabaseEntry(): array;

    /**
     * Returns a map of the entity's primary keys.
     * @return array key => value pairs
     */
    public function getPrimaryKey(): array;
}