<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerSeasonStatisticBuilder
{
    /**
     * @param IPlayerSeasonStatisticEntity[] $playerSeasonStatisticEntities
     * @return IPlayerSeasonStatisticCollection
     */
    public function build(array $playerSeasonStatisticEntities): IPlayerSeasonStatisticCollection;
}