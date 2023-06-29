<?php


namespace Sportal\FootballApi\Application\Asset\Output\Edit;



use Sportal\FootballApi\Domain\Asset\IAssetCollection;

class Mapper
{
    /**
     * @param IAssetCollection $assetCollection
     * @return CollectionDto
     */
    public function map(IAssetCollection $assetCollection): CollectionDto
    {
        $assetDtos = [];
        foreach ($assetCollection as $assetModel) {
            $assetContext = !is_null($assetModel->getEntity()->getContextType()) ? $assetModel->getEntity()
                ->getContextType()->getKey() : null;
            $assetDtos[] = new Dto(
                $assetModel->getEntity()->getEntity()->getKey(),
                $assetModel->getEntity()->getEntityId(),
                $assetModel->getEntity()->getType()->getKey(),
                $assetModel->getEntity()->getPath(),
                $assetContext,
                $assetModel->getEntity()->getContextId(),
            );
        }

        return new CollectionDto($assetDtos);
    }
}