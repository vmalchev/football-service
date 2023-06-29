<?php


namespace Sportal\FootballApi\Application\Match\Input\Update\Score;

use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_MatchScoreInput")
 */
class Dto
{
    /**
     * @SWG\Property(property="total")
     * @var TeamScoreDto
     */
    private TeamScoreDto $total;

    /**
     * @SWG\Property(property="half_time")
     * @var TeamScoreDto|null
     */
    private ?TeamScoreDto $half_time;

    /**
     * @SWG\Property(property="regular_time")
     * @var TeamScoreDto|null
     */
    private ?TeamScoreDto $regular_time;

    /**
     * @SWG\Property(property="extra_time")
     * @var TeamScoreDto|null
     */
    private ?TeamScoreDto $extra_time;

    /**
     * @SWG\Property(property="penalty_shootout")
     * @var TeamScoreDto|null
     */
    private ?TeamScoreDto $penalty_shootout;

    /**
     * @SWG\Property(property="aggregate")
     * @var TeamScoreDto|null
     */
    private ?TeamScoreDto $aggregate;

    /**
     * Dto constructor.
     * @param TeamScoreDto $total
     * @param TeamScoreDto|null $half_time
     * @param TeamScoreDto|null $regular_time
     * @param TeamScoreDto|null $extra_time
     * @param TeamScoreDto|null $penalty_shootout
     * @param TeamScoreDto|null $aggregate
     */
    public function __construct(TeamScoreDto $total,
                                ?TeamScoreDto $half_time = null,
                                ?TeamScoreDto $regular_time = null,
                                ?TeamScoreDto $extra_time = null,
                                ?TeamScoreDto $penalty_shootout = null,
                                ?TeamScoreDto $aggregate = null)
    {
        $this->total = $total;
        $this->half_time = $half_time;
        $this->regular_time = $regular_time;
        $this->extra_time = $extra_time;
        $this->penalty_shootout = $penalty_shootout;
        $this->aggregate = $aggregate;
    }

    /**
     * @return TeamScoreDto
     */
    public function getTotal(): TeamScoreDto
    {
        return $this->total;
    }

    /**
     * @return TeamScoreDto|null
     */
    public function getHalfTime(): ?TeamScoreDto
    {
        return $this->half_time;
    }

    /**
     * @return TeamScoreDto|null
     */
    public function getRegularTime(): ?TeamScoreDto
    {
        return $this->regular_time;
    }

    /**
     * @return TeamScoreDto|null
     */
    public function getExtraTime(): ?TeamScoreDto
    {
        return $this->extra_time;
    }

    /**
     * @return TeamScoreDto|null
     */
    public function getPenaltyShootout(): ?TeamScoreDto
    {
        return $this->penalty_shootout;
    }

    /**
     * @return TeamScoreDto|null
     */
    public function getAggregate(): ?TeamScoreDto
    {
        return $this->aggregate;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'total' => new Assert\Required(TeamScoreDto::getValidatorConstraints()),
            'half_time' => new Assert\Optional(TeamScoreDto::getValidatorConstraints()),
            'regular_time' => new Assert\Optional(TeamScoreDto::getValidatorConstraints()),
            'extra_time' => new Assert\Optional(TeamScoreDto::getValidatorConstraints()),
            'penalty_shootout' => new Assert\Optional(TeamScoreDto::getValidatorConstraints()),
            'aggregate' => new Assert\Optional(TeamScoreDto::getValidatorConstraints())
        ]);
    }
}