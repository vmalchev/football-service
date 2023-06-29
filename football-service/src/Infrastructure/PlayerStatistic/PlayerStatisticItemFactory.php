<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItem;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItemFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerStatisticType;

class PlayerStatisticItemFactory implements IPlayerStatisticItemFactory
{
    private PlayerStatisticType $name;
    private ?string $value;


    /**
     * @param PlayerStatisticType $name
     * @return PlayerStatisticItemFactory
     */
    public function setName(PlayerStatisticType $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param null|string $value
     * @return PlayerStatisticItemFactory
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return IPlayerStatisticItem
     */
    public function create(): IPlayerStatisticItem
    {
        return new PlayerStatisticItem(
            $this->name,
            $this->value
        );
    }
}