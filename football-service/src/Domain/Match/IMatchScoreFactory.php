<?php

namespace Sportal\FootballApi\Domain\Match;

interface IMatchScoreFactory
{
    /**
     * @param ITeamScore $total
     * @return IMatchScoreFactory
     */
    public function setTotal(ITeamScore $total): IMatchScoreFactory;

    /**
     * @param ITeamScore|null $halfTime
     * @return IMatchScoreFactory
     */
    public function setHalfTime(?ITeamScore $halfTime): IMatchScoreFactory;

    /**
     * @param ITeamScore|null $regularTime
     * @return IMatchScoreFactory
     */
    public function setRegularTime(?ITeamScore $regularTime): IMatchScoreFactory;

    /**
     * @param ITeamScore|null $extraTime
     * @return IMatchScoreFactory
     */
    public function setExtraTime(?ITeamScore $extraTime): IMatchScoreFactory;

    /**
     * @param ITeamScore|null $penaltyShootout
     * @return IMatchScoreFactory
     */
    public function setPenaltyShootout(?ITeamScore $penaltyShootout): IMatchScoreFactory;

    /**
     * @param ITeamScore|null $aggregate
     * @return IMatchScoreFactory
     */
    public function setAggregate(?ITeamScore $aggregate): IMatchScoreFactory;

    public function setEmpty(): IMatchScoreFactory;

    public function create(): IMatchScore;

}