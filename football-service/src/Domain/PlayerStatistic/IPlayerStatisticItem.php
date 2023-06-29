<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerStatisticItem
{
    /**
     * @return PlayerStatisticType
     */
    public function getName(): PlayerStatisticType;

    /**
     * @return string|null
     */
    public function getValue(): ?string;
}