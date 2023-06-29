<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Sportal\FootballApi\Domain\Match\ITeamScore;
use Sportal\FootballApi\Domain\Match\ITeamScoreFactory;

class TeamScoreFactory implements ITeamScoreFactory
{
    private int $home;
    private int $away;

    /**
     * @param int $home
     * @return ITeamScoreFactory
     */
    public function setHome(int $home): ITeamScoreFactory
    {
        $this->home = $home;
        return $this;
    }

    /**
     * @param int $away
     * @return ITeamScoreFactory
     */
    public function setAway(int $away): ITeamScoreFactory
    {
        $this->away = $away;
        return $this;
    }

    /**
     * @return ITeamScoreFactory
     */
    public function setEmpty(): ITeamScoreFactory
    {
        return new TeamScoreFactory();
    }

    /**
     * @return ITeamScore
     */
    public function create(): ITeamScore
    {
        return new TeamScore($this->home, $this->away);
    }

}