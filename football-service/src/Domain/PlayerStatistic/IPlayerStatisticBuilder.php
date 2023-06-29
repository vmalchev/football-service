<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerStatisticBuilder
{
    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticModel
     */
    public function build(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticModel;
}