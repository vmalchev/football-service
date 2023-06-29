<?php


namespace Sportal\FootballApi\Domain\Lineup;


interface ILineupProfile
{
    public function setLineup(ILineupEntity $lineupEntity): ILineupProfile;

    public function getLineup(): ILineupEntity;

    /**
     * @param ILineupPlayerEntity[] $players
     * @return ILineupProfile
     */
    public function setPlayers(array $players): ILineupProfile;

    /**
     * @return ILineupPlayerEntity[]
     */
    public function getHomePlayers(): array;

    /**
     * @return ILineupPlayerEntity[]
     */
    public function getAwayPlayers(): array;

    /**
     * @return ILineupPlayerEntity[]
     */
    public function getPlayers(): array;

    public function hasHomeTeam(): bool;

    public function hasAwayTeam(): bool;
}