<?php


namespace Sportal\FootballApi\Domain\TeamStatistic;


class TeamStatisticDtoBuilder
{
    public $played;
    public $win;
    public $draw;
    public $defeats;
    public $goalsScored;
    public $goalsConceded;

    public function __construct()
    {
        $this->played = 0;
        $this->win = 0;
        $this->draw = 0;
        $this->defeats = 0;
        $this->goalsScored = 0;
        $this->goalsConceded = 0;
    }

    public function build(): TeamStatisticAggregatorDto
    {
        return new TeamStatisticAggregatorDto($this->played, $this->win, $this->draw, $this->defeats, $this->goalsScored, $this->goalsConceded);
    }
}