<?php


namespace Sportal\FootballApi\Application\Lineup\Input\Edit;

use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @SWG\Definition(definition="v2_LineupPlayerInput")
 */
class PlayerDto implements IDto
{
    /**
     * @SWG\Property(property="type_id")
     * @var string
     */
    private string $type_id;

    /**
     * @SWG\Property(property="player_id")
     * @var string
     */
    private string $player_id;

    /**
     * @SWG\Property(property="position_x")
     * @var int|null
     */
    private ?int $position_x;

    /**
     * @SWG\Property(property="position_y")
     * @var int|null
     */
    private ?int $position_y;

    /**
     * @SWG\Property(property="shirt_number")
     * @var int|null
     */
    private ?int $shirt_number;

    /**
     * TeamDto constructor.
     * @param $type_id
     * @param $player_id
     * @param $position_x
     * @param $position_y
     * @param $shirt_number
     */
    public function __construct(string $type_id,
                                string $player_id,
                                ?int $position_x = null,
                                ?int $position_y = null,
                                ?int $shirt_number = null)
    {
        $this->type_id = $type_id;
        $this->player_id = $player_id;
        $this->position_x = $position_x;
        $this->position_y = $position_y;
        $this->shirt_number = $shirt_number;
    }

    /**
     * @return string
     */
    public function getTypeId(): string
    {
        return $this->type_id;
    }

    /**
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->player_id;
    }

    /**
     * @return int|null
     */
    public function getPositionX(): ?int
    {
        return $this->position_x;
    }

    /**
     * @return int|null
     */
    public function getPositionY(): ?int
    {
        return $this->position_y;
    }

    /**
     * @return int|null
     */
    public function getShirtNumber(): ?int
    {
        return $this->shirt_number;
    }


    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'player_id' => [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'type_id' => [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'position_x' => new Assert\Optional([new Assert\Type('int')]),
            'position_y' => new Assert\Optional([new Assert\Type('int')]),
            'shirt_number' => new Assert\Optional([new Assert\Type('int')]),
        ]);
    }
}