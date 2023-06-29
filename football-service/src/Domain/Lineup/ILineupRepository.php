<?php


namespace Sportal\FootballApi\Domain\Lineup;


interface ILineupRepository
{
    public function findByMatchId(string $matchId): ?ILineupEntity;

    public function upsert(ILineupEntity $lineup): void;
}