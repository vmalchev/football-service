<?php


namespace Sportal\FootballApi\Domain\Asset;


final class AssetModel implements IAssetModel
{
    /**
     * @var IAssetEntity
     */
    private IAssetEntity $entity;

    /**
     * @var IAssetRepository
     */
    private IAssetRepository $repository;

    /**
     * @param IAssetRepository $repository
     */
    public function __construct(IAssetRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return IAssetEntity
     */
    public function getEntity(): IAssetEntity
    {
        return $this->entity;
    }

    /**
     * @param IAssetEntity $entity
     */
    public function setEntity(IAssetEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return IAssetModel
     * @throws \Exception
     */
    public function create()
    {
        $this->repository->create($this->getEntity());
        return $this;
    }

    /**
     * @return IAssetModel
     * @throws \Exception
     */
    public function update()
    {
        $this->repository->update($this->getEntity());
        return $this;
    }

    public function delete()
    {
        $this->repository->delete($this->getEntity());
        return $this;
    }
}