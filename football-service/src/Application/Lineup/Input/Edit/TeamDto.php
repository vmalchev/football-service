<?php


namespace Sportal\FootballApi\Application\Lineup\Input\Edit;


use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_LineupTeamInput")
 */
class TeamDto implements IDto
{
    /**
     * @SWG\Property(property="formation")
     * @var string|null
     */
    private ?string $formation;

    /**
     * @SWG\Property(property="coach_id")
     * @var string|null
     */
    private ?string $coach_id;

    /**
     * @SWG\Property(property="players")
     * @var PlayerDto[]|null
     */
    private ?array $players;

    /**
     * TeamDto constructor.
     * @param string|null $formation
     * @param string|null $coach_id
     * @param PlayerDto[]|null $players
     */
    public function __construct(?string $formation = null, ?string $coach_id = null, ?array $players = null)
    {
        $this->formation = $formation;
        $this->coach_id = $coach_id;
        $this->players = $players;
    }


    /**
     * @return string|null
     */
    public function getFormation(): ?string
    {
        return $this->formation;
    }

    /**
     * @return string|null
     */
    public function getCoachId(): ?string
    {
        return $this->coach_id;
    }

    /**
     * @return PlayerDto[]
     */
    public function getPlayers(): ?array
    {
        return $this->players;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'formation' => new Assert\Optional([
                new Assert\Type('string'),
            ]),
            'coach_id' => new Assert\Optional([
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]),
            'players' => new Assert\Optional([new Assert\All([
                new Assert\Type('array'),
                PlayerDto::getValidatorConstraints()
            ])])
        ]);
    }
}