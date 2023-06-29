<?php
namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItem;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerStatisticType;

class PlayerStatisticItem implements IPlayerStatisticItem, \JsonSerializable
{
    private PlayerStatisticType $name;
    private ?string $value;

    /**
     * @param PlayerStatisticType $name
     * @param string|null $value
     */
    public function __construct(PlayerStatisticType $name, ?string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return PlayerStatisticType
     */
    public function getName(): PlayerStatisticType
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }

}