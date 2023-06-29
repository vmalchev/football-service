<?php


namespace Sportal\FootballApi\Application\Asset\Service;


use Sportal\FootballApi\Application\Asset;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Asset\IAssetCollection;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class Delete implements IService
{
    /**
     * @var IAssetCollection
     */
    private IAssetCollection $assetCollection;

    /**
     * @var ITransactionManager
     */
    private ITransactionManager $transactionManager;

    /**
     * @var Asset\Input\Delete\Mapper
     */
    private Asset\Input\Delete\Mapper $deleteInputMapper;

    /**
     * Delete constructor.
     * @param IAssetCollection $assetCollection
     * @param ITransactionManager $transactionManager
     * @param Asset\Input\Delete\Mapper $deleteInputMapper
     */
    public function __construct(IAssetCollection $assetCollection,
                                ITransactionManager $transactionManager,
                                Asset\Input\Delete\Mapper $deleteInputMapper)
    {
        $this->assetCollection = $assetCollection;
        $this->transactionManager = $transactionManager;
        $this->deleteInputMapper = $deleteInputMapper;
    }


    public function process(IDto $assetCollectionDto)
    {
        $assetEntities = $this->deleteInputMapper->map($assetCollectionDto);

        return $this->transactionManager->transactional(function () use ($assetEntities) {
            $this->assetCollection->delete($assetEntities)->removeBlacklist();
        });
    }
}