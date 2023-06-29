<?php


namespace Sportal\FootballApi\Domain\Asset;


interface IAssetEntityFactory
{
    public function setEmpty(): IAssetEntityFactory;

    public function setAssetEntity(IAssetEntity $entity): IAssetEntityFactory;

    public function create(): IAssetEntity;

    public function createFromArray(array $data): IAssetEntity;

    public function setId(string $id): IAssetEntityFactory;

    public function setType(AssetType $type): IAssetEntityFactory;

    public function setEntity(AssetEntityType $entity): IAssetEntityFactory;

    public function setEntityId(string $entityId): IAssetEntityFactory;

    public function setContextType(?AssetContextType $contextType): IAssetEntityFactory;

    public function setContextId(?string $contextId): IAssetEntityFactory;

    public function setPath(string $path): IAssetEntityFactory;
}