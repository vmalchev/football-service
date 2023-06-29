<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerSeasonStatisticCollection extends \IteratorAggregate
{
    public function setCollection(array $playerSeasonStatisticEntities): self;
}