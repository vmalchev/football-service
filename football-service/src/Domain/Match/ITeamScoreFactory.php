<?php

namespace Sportal\FootballApi\Domain\Match;

interface ITeamScoreFactory
{
    /**
     * @param int $home
     * @return ITeamScoreFactory
     */
    public function setHome(int $home): ITeamScoreFactory;

    /**
     * @param int $away
     * @return ITeamScoreFactory
     */
    public function setAway(int $away): ITeamScoreFactory;

    /**
     * @return ITeamScoreFactory
     */
    public function setEmpty(): ITeamScoreFactory;

    /**
     * @return ITeamScore
     */
    public function create(): ITeamScore;
}