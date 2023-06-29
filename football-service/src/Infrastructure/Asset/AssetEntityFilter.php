<?php


namespace Sportal\FootballApi\Infrastructure\Asset;


use Sportal\FootballApi\Domain\Asset\AssetEntityType;

class AssetEntityFilter
{
    private AssetEntityType $entity;

    private string $entityId;

    /**
     * AssetEntityFilter constructor.
     * @param AssetEntityType $entity
     * @param string $entityId
     */
    public function __construct(AssetEntityType $entity, string $entityId)
    {
        $this->entity = $entity;
        $this->entityId = $entityId;
    }

    /**
     * @return AssetEntityType
     */
    public function getEntity(): AssetEntityType
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function __toString()
    {
        return "{$this->entity->getValue()}-$this->entityId";
    }

}