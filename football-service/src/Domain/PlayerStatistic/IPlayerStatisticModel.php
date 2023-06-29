<?php
namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerStatisticModel
{
    /**
     * @return IPlayerStatisticEntity
     */
    public function getPlayerStatisticEntity(): IPlayerStatisticEntity;

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     */
    public function setPlayerStatisticEntity(IPlayerStatisticEntity $playerStatisticEntity): void;
}