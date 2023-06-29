<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Get;

use Sportal\FootballApi\Application\PlayerStatistic;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticCollection;

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
     * @param IPlayerSeasonStatisticCollection $playerSeasonStatisticCollection
     * @return SeasonStatisticDto
     */
    public function map(IPlayerSeasonStatisticCollection $playerSeasonStatisticCollection): SeasonStatisticDto
    {
        $dtoCollection = [];
        foreach ($playerSeasonStatisticCollection as $playerSeasonStatisticEntity) {
            $dtoCollection[] = $this->mapper->map($playerSeasonStatisticEntity);
        }
        return new SeasonStatisticDto($dtoCollection);
    }
}