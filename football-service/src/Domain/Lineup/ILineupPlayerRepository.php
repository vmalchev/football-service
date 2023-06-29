<?php


namespace Sportal\FootballApi\Domain\Lineup;


interface ILineupPlayerRepository
{
    /**
     * @param ILineupEntity $lineupEntity
     * @return ILineupPlayerEntity[]
     */
    public function findByLineup(ILineupEntity $lineupEntity): array;

    /**
     * @param ILineupEntity $lineupEntity
     * @param ILineupPlayerEntity[] $players
     * @return void
     */
    public function upsertByLineup(ILineupEntity $lineupEntity, array $players): void;
}