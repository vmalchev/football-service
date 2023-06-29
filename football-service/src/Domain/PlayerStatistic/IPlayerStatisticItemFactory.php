<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerStatisticItemFactory
{

    /**
     * @param PlayerStatisticType $name
     * @return IPlayerStatisticItemFactory
     */
    public function setName(PlayerStatisticType $name): self;

    /**
     * @param string|null $value
     * @return IPlayerStatisticItemFactory
     */
    public function setValue(?string $value): self;

    /**
     * @return IPlayerStatisticItem
     */
    public function create(): IPlayerStatisticItem;
}