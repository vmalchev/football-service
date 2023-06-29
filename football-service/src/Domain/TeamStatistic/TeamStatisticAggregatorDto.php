<?php


namespace Sportal\FootballApi\Domain\TeamStatistic;


class TeamStatisticAggregatorDto
{
    private $played;
    private $win;
    private $draw;
    private $defeats;
    private $goalsScored;
    private $goalsConceded;

    /**
     * @param $played
     * @param $win
     * @param $draw
     * @param $defeats
     * @param goalsScored
     * @param goalsConceded
     */
    public function __construct($played, $win, $draw, $defeats, $goalsScored, $goalsConceded)
    {
        $this->played = $played;
        $this->win = $win;
        $this->draw = $draw;
        $this->defeats = $defeats;
        $this->goalsScored = $goalsScored;
        $this->goalsConceded = $goalsConceded;
    }

    /**
     * @return mixed
     */
    public function getPlayed()
    {
        return $this->played;
    }

    /**
     * @return mixed
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * @return mixed
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @return mixed
     */
    public function getDefeats()
    {
        return $this->defeats;
    }

    /**
     * @return mixed
     */
    public function getGoalsScored()
    {
        return $this->goalsScored;
    }

    /**
     * @return mixed
     */
    public function getGoalsConceded()
    {
        return $this->goalsConceded;
    }
}