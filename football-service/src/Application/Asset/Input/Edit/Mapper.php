<?php


namespace Sportal\FootballApi\Application\Asset\Input\Edit;


use Sportal\FootballApi\Domain\Asset\AssetContextType;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Asset\IAssetEntity;
use Sportal\FootballApi\Domain\Asset\IAssetEntityFactory;

class Mapper
{
    private IAssetEntityFactory $assetFactory;

    public function __construct(IAssetEntityFactory $assetFactory)
    {
        $this->assetFactory = $assetFactory;
    }

    /**
     * @param CollectionDto $assetCollectionDto
     * @return IAssetEntity[]
     */
    public function map(CollectionDto $assetCollectionDto): array
    {
        $assetEntityCollection = [];
        foreach ($assetCollectionDto->getAssetDtos() as $assetDto) {
            $contextType = !is_null($assetDto->getContextType()) ? AssetContextType::{$assetDto->getContextType()}() : null;

            $assetEntityCollection[] = $this->assetFactory->setEmpty()
                ->setEntityId($assetDto->getEntityId())
                ->setEntity(AssetEntityType::{$assetDto->getEntity()}())
                ->setType(AssetType::{$assetDto->getType()}())
                ->setPath($assetDto->getPath())
                ->setContextType($contextType)
                ->setContextId($assetDto->getContextId())
                ->create();
        }

        return $assetEntityCollection;
    }
}