<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Upsert;

use Sportal\FootballApi\Application\PlayerStatistic;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticCollection;

class CollectionMapper
{
    private Mapper $mapper;

    /**
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param IPlayerStatisticCollection $playerStatisticCollection
     * @return CollectionDto
     */
    public function map(IPlayerStatisticCollection $playerStatisticCollection): CollectionDto
    {
        $dtoCollection = [];
        foreach ($playerStatisticCollection as $playerStatisticModel) {
            $dtoCollection[] = $this->mapper->map($playerStatisticModel->getPlayerStatisticEntity());
        }

        return new CollectionDto($dtoCollection);
    }
}