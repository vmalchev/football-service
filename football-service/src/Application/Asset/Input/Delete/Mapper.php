<?php


namespace Sportal\FootballApi\Application\Asset\Input\Delete;


use Sportal\FootballApi\Application\Asset\Input\Edit\CollectionDto;
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
            $assetEntityCollection[] = $this->assetFactory->setEmpty()
                ->setEntityId($assetDto->getEntityId())
                ->setEntity(AssetEntityType::{$assetDto->getEntity()}())
                ->setType(AssetType::{$assetDto->getType()}())
                ->setContextType(
                    !is_null($assetDto->getContextType())
                    ? AssetContextType::{$assetDto->getContextType()}()
                    : null
                )
                ->setContextId($assetDto->getContextId())
                ->create();
        }

        return $assetEntityCollection;
    }
}