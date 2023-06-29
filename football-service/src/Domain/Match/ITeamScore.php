<?php


namespace Sportal\FootballApi\Domain\Match;


interface ITeamScore
{
    public function getHome(): int;

    public function getAway(): int;
}