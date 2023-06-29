<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


final class PlayerSeasonStatisticCollection implements IPlayerSeasonStatisticCollection
{
    private array $playerSeasonStatisticEntities;

    public function setCollection(array $playerSeasonStatisticEntities): self {
        $collection = clone $this;
        $this->playerSeasonStatisticEntities = $playerSeasonStatisticEntities;
        return $collection;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->playerSeasonStatisticEntities);
    }
}