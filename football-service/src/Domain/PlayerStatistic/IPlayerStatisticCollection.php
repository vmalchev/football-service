<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerStatisticCollection extends \IteratorAggregate
{
    /**
     * @param IPlayerStatisticEntity[] $playerStatisticEntities
     * @return IPlayerStatisticCollection
     */
    public function upsert(array $playerStatisticEntities): IPlayerStatisticCollection;
}