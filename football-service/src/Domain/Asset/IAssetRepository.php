<?php


namespace Sportal\FootballApi\Domain\Asset;


interface IAssetRepository
{
    /**
     * @param IAssetEntity $assetEntity
     * @return IAssetEntity
     * @throws \Exception
     */
    public function update(IAssetEntity $assetEntity): IAssetEntity;

    /**
     * @param IAssetEntity $assetEntity
     * @return IAssetEntity
     * @throws \Exception
     */
    public function create(IAssetEntity $assetEntity): IAssetEntity;

    /**
     * @param IAssetEntity $assetEntity
     * @throws \Exception
     */
    public function delete(IAssetEntity $assetEntity): void;

    /**
     * @param IAssetEntity $assetEntity
     * @return IAssetEntity|null
     */
    public function find(IAssetEntity $assetEntity): ?IAssetEntity;

    /**
     * @param string $entityType
     * @param array $primaryKey
     * @return bool
     */
    public function entityExists(string $entityType, array $primaryKey): bool;
}