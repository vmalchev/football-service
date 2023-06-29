<?php


namespace Sportal\FootballApi\Domain\Lineup;


interface ILineupProfileBuilder
{
    public function build(string $matchId): ?ILineupProfile;
}