<?php


namespace Sportal\FootballApi\Domain\Asset;


interface IAssetCollection extends \IteratorAggregate
{
    /**
     * @param IAssetEntity[] $assetEntities
     * @return IAssetCollection
     */
    public function upsert(array $assetEntities): IAssetCollection;

    /**
     * @param array $assetEntities
     * @return IAssetCollection
     */
    public function delete(array $assetEntities): IAssetCollection;

    /**
     * @return IAssetCollection
     */
    public function withBlacklist(): IAssetCollection;

    /**
     * @return IAssetCollection
     */
    public function removeBlacklist(): IAssetCollection;
}