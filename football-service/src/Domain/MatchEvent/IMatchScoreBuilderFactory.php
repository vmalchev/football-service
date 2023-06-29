<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


interface IMatchScoreBuilderFactory
{
    public function create(): IMatchScoreBuilder;
}