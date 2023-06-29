<?php


namespace Sportal\FootballApi\Application\Standing\Input\Put\League;

use App\Validation\Identifier;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_LeagueEntryInput")
 */
class Dto
{
    /**
     * @SWG\Property(property="rank")
     * @var int
     */
    private int $rank;

    /**
     * @SWG\Property(property="team_id")
     * @var string
     */
    private string $team_id;

    /**
     * @SWG\Property(property="played")
     * @var int
     */
    private int $played;

    /**
     * @SWG\Property(property="wins")
     * @var int
     */
    private int $wins;

    /**
     * @SWG\Property(property="draws")
     * @var int
     */
    private int $draws;

    /**
     * @SWG\Property(property="losses")
     * @var int
     */
    private int $losses;

    /**
     * @SWG\Property(property="points")
     * @var int
     */
    private int $points;

    /**
     * @SWG\Property(property="goals_for")
     * @var int
     */
    private int $goals_for;

    /**
     * @SWG\Property(property="goals_against")
     * @var int
     */
    private int $goals_against;

    /**
     * LeagueStandingDto constructor.
     * @param int $rank
     * @param string $team_id
     * @param int $played
     * @param int $wins
     * @param int $draws
     * @param int $losses
     * @param int $points
     * @param int $goals_for
     * @param int $goals_against
     */
    public function __construct(int $rank,
                                string $team_id,
                                int $played,
                                int $wins,
                                int $draws,
                                int $losses,
                                int $points,
                                int $goals_for,
                                int $goals_against)
    {
        $this->rank = $rank;
        $this->team_id = $team_id;
        $this->played = $played;
        $this->wins = $wins;
        $this->draws = $draws;
        $this->losses = $losses;
        $this->points = $points;
        $this->goals_for = $goals_for;
        $this->goals_against = $goals_against;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->team_id;
    }

    /**
     * @return int
     */
    public function getPlayed(): int
    {
        return $this->played;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @return int
     */
    public function getDraws(): int
    {
        return $this->draws;
    }

    /**
     * @return int
     */
    public function getLosses(): int
    {
        return $this->losses;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getGoalsFor(): int
    {
        return $this->goals_for;
    }

    /**
     * @return int
     */
    public function getGoalsAgainst(): int
    {
        return $this->goals_against;
    }


    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'team_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'rank' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(1)
            ],
            'played' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'wins' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'draws' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'losses' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'points' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'goals_for' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ],
            'goals_against' => [
                new Assert\NotBlank(),
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ]
        ]);

    }

}