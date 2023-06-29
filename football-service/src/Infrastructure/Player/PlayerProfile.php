<?php


namespace Sportal\FootballApi\Infrastructure\Player;

use JsonSerializable;
use Sportal\FootballApi\Application\Player\Dto\PlayerProfileDto;
use Sportal\FootballApi\Domain\Player\IPlayerProfile;

class PlayerProfile implements IPlayerProfile, JsonSerializable
{
    /**
     * @var int|null
     */
    private ?int $height;

    /**
     * @var int|null
     */
    private ?int $weight;

    /**
     * PlayerProfile constructor.
     * @param int/null $height
     * @param int/null $weight
     */
    public function __construct(?int $height, ?int $weight)
    {
        $this->height = $height;
        $this->weight = $weight;
    }

    public static function fromPlayerProfileDto(PlayerProfileDto $playerProfileDto): PlayerProfile
    {
        return new PlayerProfile($playerProfileDto->getHeight(), $playerProfileDto->getWeight());
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }

}