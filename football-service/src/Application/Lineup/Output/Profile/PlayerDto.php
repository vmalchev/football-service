<?php

namespace Sportal\FootballApi\Application\Lineup\Output\Profile;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Player;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_LineupPlayerDto")
 */
class PlayerDto implements IDto, JsonSerializable
{
    /**
     * @SWG\Property(property="type")
     * @var PlayerTypeDto
     */
    private PlayerTypeDto $type;

    /**
     * @var Player\Output\Get\Dto|null
     * @SWG\Property(property="player")
     */
    private ?Player\Output\Get\Dto $player;

    /**
     * @var int|null
     * @SWG\Property(property="position_x")
     */
    private ?int $position_x;

    /**
     * @var int|null
     * @SWG\Property(property="position_y")
     */
    private ?int $position_y;

    /**
     * @var int|null
     * @SWG\Property(property="shirt_number")
     */
    private ?int $shirt_number;

    /**
     * PlayerDto constructor.
     * @param PlayerTypeDto $type
     * @param Player\Output\Get\Dto|null $player
     * @param int|null $position_x
     * @param int|null $position_y
     * @param int|null $shirt_number
     */
    public function __construct(PlayerTypeDto $type,
                                ?Player\Output\Get\Dto $player,
                                ?int $position_x,
                                ?int $position_y,
                                ?int $shirt_number)
    {
        $this->type = $type;
        $this->player = $player;
        $this->position_x = $position_x;
        $this->position_y = $position_y;
        $this->shirt_number = $shirt_number;
    }


    /**
     * @return string
     */
    public function getTypeId(): string
    {
        return $this->type;
    }

    /**
     * @return Player\Output\Get\Dto
     */
    public function getPlayer(): Player\Output\Get\Dto
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getPositionX(): int
    {
        return $this->position_x;
    }

    /**
     * @return int
     */
    public function getPositionY(): int
    {
        return $this->position_y;
    }

    /**
     * @return int
     */
    public function getShirtNumber(): int
    {
        return $this->shirt_number;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}