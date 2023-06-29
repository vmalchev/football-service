<?php


namespace Sportal\FootballApi\Infrastructure\Asset;


use Sportal\FootballApi\Domain\Asset\AssetContextType;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Asset\IAssetEntity;
use Sportal\FootballApi\Domain\Asset\IAssetEntityFactory;

class AssetEntityFactory implements IAssetEntityFactory
{
    private ?string $id;
    private AssetType $type;
    private AssetEntityType $entity;
    private string $entityId;
    private ?string $path;
    private ?AssetContextType $contextType;
    private ?string $contextId;

    public function setEmpty(): IAssetEntityFactory
    {
        return new AssetEntityFactory();
    }

    public function setAssetEntity(IAssetEntity $entity): IAssetEntityFactory
    {
        $factory = new AssetEntityFactory();

        $factory->id = $entity->getId();
        $factory->type = $entity->getType();
        $factory->entity = $entity->getEntity();
        $factory->entityId = $entity->getEntityId();
        $factory->path = $entity->getPath();
        $factory->contextType = $entity->getContextType();
        $factory->contextId = $entity->getContextId();

        return $factory;
    }

    public function create(): IAssetEntity
    {
        return new AssetEntity(
            isset($this->id) ? $this->id : null,
            $this->type,
            $this->entity,
            $this->entityId,
            isset($this->path) ? $this->path : null,
            isset($this->contextType) ? $this->contextType : null,
            isset($this->contextId) ? $this->contextId : null
        );
    }

    public function createFromArray(array $data): IAssetEntity
    {
        $factory = new AssetEntityFactory();

        $factory->id = $data[AssetTable::FIELD_ID];
        $factory->type = new AssetType($data[AssetTable::FIELD_TYPE]);
        $factory->entity = new AssetEntityType($data[AssetTable::FIELD_ENTITY]);
        $factory->entityId = $data[AssetTable::FIELD_ENTITY_ID];
        $factory->path = $data[AssetTable::FIELD_PATH];
        $factory->contextType = !is_null($data[AssetTable::FIELD_CONTEXT_TYPE]) ? new AssetContextType
    ($data[AssetTable::FIELD_CONTEXT_TYPE]) : null;
        $factory->contextId = $data[AssetTable::FIELD_CONTEXT_ID];

        return $factory->create();
    }

    public function setId(string $id): IAssetEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setType(AssetType $type): IAssetEntityFactory
    {
        $this->type = $type;
        return $this;
    }

    public function setEntity(AssetEntityType $entity): IAssetEntityFactory
    {
        $this->entity = $entity;
        return $this;
    }

    public function setEntityId(string $entityId): IAssetEntityFactory
    {
        $this->entityId = $entityId;
        return $this;
    }

    public function setPath(?string $path): IAssetEntityFactory
    {
        $this->path = $path;
        return $this;
    }

    public function setContextType(?AssetContextType $contextType): IAssetEntityFactory
    {
        $this->contextType = $contextType;
        return $this;
    }

    public function setContextId(?string $contextId): IAssetEntityFactory
    {
        $this->contextId = $contextId;
        return $this;
    }
}
