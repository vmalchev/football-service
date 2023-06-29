<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\IMatchScoreFactory;
use Sportal\FootballApi\Domain\Match\ITeamScore;

class MatchScoreFactory implements IMatchScoreFactory
{
    private ITeamScore $total;

    private ?ITeamScore $halfTime = null;

    private ?ITeamScore $regularTime = null;

    private ?ITeamScore $extraTime = null;

    private ?ITeamScore $penaltyShootout = null;

    private ?ITeamScore $aggregate = null;

    /**
     * @param ITeamScore $total
     * @return IMatchScoreFactory
     */
    public function setTotal(ITeamScore $total): IMatchScoreFactory
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @param ITeamScore|null $halfTime
     * @return IMatchScoreFactory
     */
    public function setHalfTime(?ITeamScore $halfTime): IMatchScoreFactory
    {
        $this->halfTime = $halfTime;
        return $this;
    }

    /**
     * @param ITeamScore|null $regularTime
     * @return IMatchScoreFactory
     */
    public function setRegularTime(?ITeamScore $regularTime): IMatchScoreFactory
    {
        $this->regularTime = $regularTime;
        return $this;
    }

    /**
     * @param ITeamScore|null $extraTime
     * @return IMatchScoreFactory
     */
    public function setExtraTime(?ITeamScore $extraTime): IMatchScoreFactory
    {
        $this->extraTime = $extraTime;
        return $this;
    }

    /**
     * @param ITeamScore|null $penaltyShootout
     * @return IMatchScoreFactory
     */
    public function setPenaltyShootout(?ITeamScore $penaltyShootout): IMatchScoreFactory
    {
        $this->penaltyShootout = $penaltyShootout;
        return $this;
    }

    /**
     * @param ITeamScore|null $aggregate
     * @return IMatchScoreFactory
     */
    public function setAggregate(?ITeamScore $aggregate): IMatchScoreFactory
    {
        $this->aggregate = $aggregate;
        return $this;
    }

    public function create(): IMatchScore
    {
        return new MatchScore(
            $this->total,
            $this->halfTime,
            $this->regularTime,
            $this->extraTime,
            $this->penaltyShootout,
            $this->aggregate
        );
    }

    public function setEmpty(): IMatchScoreFactory
    {
        return new MatchScoreFactory();
    }
}