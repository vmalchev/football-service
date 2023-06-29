<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerSeasonStatisticRepository
{

    /**
     * @param PlayerSeasonStatisticFilter $filter
     * @return mixed
     */
    public function getByFilter(PlayerSeasonStatisticFilter $filter);
}