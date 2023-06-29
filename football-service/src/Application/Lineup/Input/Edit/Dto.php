<?php


namespace Sportal\FootballApi\Application\Lineup\Input\Edit;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Lineup\LineupStatus;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_LineupInput")
 */
class Dto implements IDto
{
    private ?string $match_id;

    /**
     * @SWG\Property(enum=LINEUP_CONFIRMED, property="status")
     * @var string
     */
    private ?string $status;

    /**
     * @SWG\Property(property="home_team")
     * @var TeamDto|null
     */
    private ?TeamDto $home_team;

    /**
     * @SWG\Property(property="away_team")
     * @var TeamDto|null
     */
    private ?TeamDto $away_team;

    /**
     * Dto constructor.
     * @param string|null $match_id
     * @param string|null $status
     * @param TeamDto|null $home_team
     * @param TeamDto|null $away_team
     */
    public function __construct(?string $match_id = null, ?string $status = null, ?TeamDto $home_team = null, ?TeamDto $away_team = null)
    {
        $this->match_id = $match_id;
        $this->status = $status;
        $this->home_team = $home_team;
        $this->away_team = $away_team;
    }


    /**
     * @return string|null
     */
    public function getMatchId(): ?string
    {
        return $this->match_id;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @return TeamDto|null
     */
    public function getHomeTeam(): ?TeamDto
    {
        return $this->home_team;
    }

    /**
     * @return TeamDto|null
     */
    public function getAwayTeam(): ?TeamDto
    {
        return $this->away_team;
    }

    /**
     * @param string|null $match_id
     * @return Dto
     */
    public function setMatchId(?string $match_id): Dto
    {
        $dto = clone $this;
        $dto->match_id = $match_id;
        return $dto;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'status' => new Assert\Choice([
                'choices' => array_values(LineupStatus::keys()),
                'message' => 'Choose a valid position. Options are: ' . implode(", ", LineupStatus::keys()),
            ]),
            'home_team' => new Assert\Optional(TeamDto::getValidatorConstraints()),
            'away_team' => new Assert\Optional(TeamDto::getValidatorConstraints()),
        ]);
    }
}