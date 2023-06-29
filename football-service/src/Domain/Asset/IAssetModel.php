<?php


namespace Sportal\FootballApi\Domain\Asset;


interface IAssetModel
{
    /**
     * @return IAssetModel
     * @throws \Exception
     */
    public function create();

    /**
     * @return IAssetModel
     * @throws \Exception
     */
    public function update();

    /**
     * @return IAssetModel
     * @throws \Exception
     */
    public function delete();

    /**
     * @return IAssetEntity
     */
    public function getEntity(): IAssetEntity;

    /**
     * @param IAssetEntity $entity
     */
    public function setEntity(IAssetEntity $entity);
}