<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Sportal\FootballApi\Domain\Match\ITeamScore;

class TeamScore implements ITeamScore
{
    private int $home;
    private int $away;

    /**
     * TeamScore constructor.
     * @param int $home
     * @param int $away
     */
    public function __construct(int $home, int $away)
    {
        $this->home = $home;
        $this->away = $away;
    }

    /**
     * @return int
     */
    public function getHome(): int
    {
        return $this->home;
    }

    /**
     * @return int
     */
    public function getAway(): int
    {
        return $this->away;
    }


}