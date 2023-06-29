<?php


namespace Sportal\FootballApi\Infrastructure\Database;


/**
 * Super class for entities that do not have a natural key
 */
abstract class GeneratedIdDatabaseEntity implements IDatabaseEntity
{
    /**
     * @inheritDoc
     */
    public function getPrimaryKey(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * Return the unique id of the entity
     * @return string
     */
    public abstract function getId(): ?string;

    /**
     * Return an instance of the object with the $id set to specified param
     * @param string $id value for unique id field
     * @return GeneratedIdDatabaseEntity
     */
    public abstract function withId(string $id): GeneratedIdDatabaseEntity;

    /**
     * @inheritDoc
     */
    public abstract function getDatabaseEntry(): array;

}