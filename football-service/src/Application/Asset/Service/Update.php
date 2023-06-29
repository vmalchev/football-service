<?php


namespace Sportal\FootballApi\Application\Asset\Service;


use Sportal\FootballApi\Application\Asset;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Asset\IAssetCollection;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

final class Update implements IService
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
     * @var Asset\Input\Edit\Mapper
     */
    private Asset\Input\Edit\Mapper $inputMapper;

    /**
     * @var Asset\Output\Edit\Mapper
     */
    private Asset\Output\Edit\Mapper $outputMapper;

    public function __construct(
        IAssetCollection $assetCollection,
        ITransactionManager $transactionManager,
        Asset\Input\Edit\Mapper $inputMapper,
        Asset\Output\Edit\Mapper $outputMapper
    ) {
        $this->assetCollection = $assetCollection;
        $this->transactionManager = $transactionManager;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
    }

    public function process(IDto $assetCollectionDto)
    {
        $assetEntities = $this->inputMapper->map($assetCollectionDto);

        return $this->transactionManager->transactional(function () use ($assetEntities) {
            return $this->outputMapper->map($this->assetCollection->upsert($assetEntities)->withBlacklist());
        });
    }
}