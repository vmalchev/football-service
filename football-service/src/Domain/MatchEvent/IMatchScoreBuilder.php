<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use Sportal\FootballApi\Domain\Match\ITeamScore;

interface IMatchScoreBuilder
{
    public function addEvent(IMatchEventEntity $event): void;

    public function getTotalScore(): ITeamScore;
}