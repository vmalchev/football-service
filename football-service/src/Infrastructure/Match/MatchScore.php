<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\ITeamScore;

class MatchScore implements IMatchScore
{
    private ITeamScore $total;

    private ?ITeamScore $halfTime;

    private ?ITeamScore $regularTime;

    private ?ITeamScore $extraTime;

    private ?ITeamScore $penaltyShootout;

    private ?ITeamScore $aggregate;

    /**
     * MatchScore constructor.
     * @param ITeamScore $total
     * @param ITeamScore|null $halfTime
     * @param ITeamScore|null $regularTime
     * @param ITeamScore|null $extraTime
     * @param ITeamScore|null $penaltyShootout
     * @param ITeamScore|null $aggregate
     */
    public function __construct(ITeamScore $total, ?ITeamScore $halfTime, ?ITeamScore $regularTime, ?ITeamScore $extraTime, ?ITeamScore $penaltyShootout, ?ITeamScore $aggregate)
    {
        $this->total = $total;
        $this->halfTime = $halfTime;
        $this->regularTime = $regularTime;
        $this->extraTime = $extraTime;
        $this->penaltyShootout = $penaltyShootout;
        $this->aggregate = $aggregate;
    }

    /**
     * @return ITeamScore
     */
    public function getTotal(): ITeamScore
    {
        return $this->total;
    }

    /**
     * @return ITeamScore|null
     */
    public function getHalfTime(): ?ITeamScore
    {
        return $this->halfTime;
    }

    /**
     * @return ITeamScore|null
     */
    public function getRegularTime(): ?ITeamScore
    {
        return $this->regularTime;
    }

    /**
     * @return ITeamScore|null
     */
    public function getExtraTime(): ?ITeamScore
    {
        return $this->extraTime;
    }

    /**
     * @return ITeamScore|null
     */
    public function getPenaltyShootout(): ?ITeamScore
    {
        return $this->penaltyShootout;
    }

    /**
     * @return ITeamScore|null
     */
    public function getAggregate(): ?ITeamScore
    {
        return $this->aggregate;
    }
}