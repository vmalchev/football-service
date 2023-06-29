<?php


namespace Sportal\FootballApi\Domain\Asset;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;

final class AssetBuilder implements IAssetBuilder
{
    private IAssetModel $assetModel;

    private IAssetRepository $repository;

    public function __construct(IAssetModel $assetModel, IAssetRepository $repository)
    {
        $this->assetModel = $assetModel;
        $this->repository = $repository;
    }

    public function build(IAssetEntity $assetEntity): IAssetModel
    {
        if (!$this->repository->entityExists($assetEntity->getEntity()->getValue(), ['id' => $assetEntity->getEntityId()])) {
            throw new NoSuchEntityException($assetEntity->getEntity()->getKey() . ' with id: ' .
                $assetEntity->getEntityId());
        }

        if (
            !is_null($assetEntity->getContextId()) &&
            !is_null($assetEntity->getContextType()) &&
            !$this->repository->entityExists(
                $assetEntity->getContextType()->getValue(),
                ['id' => $assetEntity->getContextId()]
            )
        ) {
            throw new NoSuchEntityException($assetEntity->getContextType() . ' with id: ' .
                $assetEntity->getContextId());
        }

        $assetModel = clone $this->assetModel;

        $assetModel->setEntity($assetEntity);

        return $assetModel;
    }
}