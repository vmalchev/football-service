<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Service;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\PlayerStatistic\Input;
use Sportal\FootballApi\Application\PlayerStatistic\Output;
use Sportal\FootballApi\Domain\Database\ITransactionManager;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticCollection;

class Upsert implements IService
{
    private IPlayerStatisticCollection $playerStatisticCollection;
    private Input\Upsert\CollectionMapper $inputCollectionMapper;
    private Output\Upsert\CollectionMapper $outputCollectionMapper;

    private ITransactionManager $transactionManager;

    /**
     * @param IPlayerStatisticCollection $playerStatisticCollection
     * @param Input\Upsert\CollectionMapper $inputCollectionMapper
     * @param Output\Upsert\CollectionMapper $outputCollectionMapper
     * @param ITransactionManager $transactionManager
     */
    public function __construct(
        IPlayerStatisticCollection $playerStatisticCollection,
        Input\Upsert\CollectionMapper $inputCollectionMapper,
        Output\Upsert\CollectionMapper $outputCollectionMapper,
        ITransactionManager $transactionManager
    ) {
        $this->playerStatisticCollection = $playerStatisticCollection;
        $this->inputCollectionMapper = $inputCollectionMapper;
        $this->outputCollectionMapper = $outputCollectionMapper;
        $this->transactionManager = $transactionManager;
    }

    public function process(IDto $playerStatisticCollectionDto)
    {
        return $this->transactionManager->transactional(
            function () use ($playerStatisticCollectionDto) {
                return $this->outputCollectionMapper->map($this->playerStatisticCollection->upsert(
                    $this->inputCollectionMapper->map($playerStatisticCollectionDto)
                ));
            }
        );
    }
}