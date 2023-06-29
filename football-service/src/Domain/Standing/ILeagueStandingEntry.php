<?php


namespace Sportal\FootballApi\Domain\Standing;


interface ILeagueStandingEntry extends IStandingEntry
{
    public function getPlayed(): int;

    public function getWins(): int;

    public function getDraws(): int;

    public function getLosses(): int;

    public function getPoints(): int;

    public function getGoalsFor(): int;

    public function getGoalsAgainst(): int;
}