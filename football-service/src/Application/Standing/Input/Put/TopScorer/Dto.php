<?php


namespace Sportal\FootballApi\Application\Standing\Input\Put\TopScorer;

use App\Validation\Identifier;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_TopScorerEntryInput")
 */
class Dto
{
    /**
     * @SWG\Property(property="team_id")
     * @var string
     */
    private string $team_id;

    /**
     * @SWG\Property(property="player_id")
     * @var string
     */
    private string $player_id;

    /**
     * @SWG\Property(property="rank")
     * @var int
     */
    private int $rank;

    /**
     * @SWG\Property(property="goals")
     * @var int
     */
    private int $goals;

    /**
     * @SWG\Property(property="played")
     * @var int|null
     */
    private ?int $played;

    /**
     * @SWG\Property(property="assists")
     * @var int|null
     */
    private ?int $assists;

    /**
     * @SWG\Property(property="scored_first")
     * @var int|null
     */
    private ?int $scored_first;

    /**
     * @SWG\Property(property="minutes")
     * @var int|null
     */
    private ?int $minutes;

    /**
     * @SWG\Property(property="penalties")
     * @var int|null
     */
    private ?int $penalties;

    /**
     * @SWG\Property(property="yellow_cards")
     * @var int|null
     */
    private ?int $yellow_cards;

    /**
     * @SWG\Property(property="red_cards")
     * @var int|null
     */
    private ?int $red_cards;


    /**
     * TopScorerEntryDto constructor.
     * @param string $team_id
     * @param string $player_id
     * @param int $rank
     * @param int $goals
     * @param int|null $played
     * @param int|null $assists
     * @param int|null $scored_first
     * @param int|null $minutes
     * @param int|null $penalties
     * @param int|null $yellow_cards
     * @param int|null $red_cards
     */
    public function __construct(
        string $team_id,
        string $player_id,
        int $rank,
        int $goals,
        ?int $played = null,
        ?int $assists = null,
        ?int $scored_first = null,
        ?int $minutes = null,
        ?int $penalties = null,
        ?int $yellow_cards = null,
        ?int $red_cards = null
    ) {
        $this->team_id = $team_id;
        $this->player_id = $player_id;
        $this->rank = $rank;
        $this->goals = $goals;
        $this->played = $played;
        $this->assists = $assists;
        $this->scored_first = $scored_first;
        $this->minutes = $minutes;
        $this->penalties = $penalties;
        $this->yellow_cards = $yellow_cards;
        $this->red_cards = $red_cards;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->team_id;
    }

    /**
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->player_id;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return int
     */
    public function getGoals(): int
    {
        return $this->goals;
    }

    /**
     * @return int|null
     */
    public function getPlayed(): ?int
    {
        return $this->played;
    }

    /**
     * @return int|null
     */
    public function getAssists(): ?int
    {
        return $this->assists;
    }

    /**
     * @return int|null
     */
    public function getScoredFirst(): ?int
    {
        return $this->scored_first;
    }

    /**
     * @return int|null
     */
    public function getMinutes(): ?int
    {
        return $this->minutes;
    }

    /**
     * @return int|null
     */
    public function getPenalties(): ?int
    {
        return $this->penalties;
    }

    /**
     * @return int|null
     */
    public function getYellowCards(): ?int
    {
        return $this->yellow_cards;
    }

    /**
     * @return int|null
     */
    public function getRedCards(): ?int
    {
        return $this->red_cards;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'team_id' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'player_id' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'rank' => [
                    new Assert\NotBlank(),
                    new Assert\Type('int'),
                    new Assert\GreaterThanOrEqual(1)
                ],
                'goals' => [
                    new Assert\Type('int'),
                    new Assert\GreaterThanOrEqual(0)
                ],
                'played' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                ),
                'assists' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                ),
                'minutes' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                ),
                'penalties' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                ),
                'scored_first' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                ),
                'yellow_cards' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                ),
                'red_cards' => new Assert\Optional(
                    [
                        new Assert\Type('int'),
                        new Assert\GreaterThanOrEqual(0)
                    ]
                )
            ]
        );
    }


}