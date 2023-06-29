<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;

interface ILineupPlayerEntityFactory
{
    public function setEntity(ILineupPlayerEntity $lineupPlayerEntity): ILineupPlayerEntityFactory;

    public function setEmpty(): ILineupPlayerEntityFactory;

    public function setMatchId(string $matchId): ILineupPlayerEntityFactory;

    public function setPlayerName(?string $playerName): ILineupPlayerEntityFactory;

    public function setTypeId(string $lineupPlayerTypeId): ILineupPlayerEntityFactory;

    public function setPlayerId(string $playerId): ILineupPlayerEntityFactory;

    public function setPlayer(?IPlayerEntity $player): ILineupPlayerEntityFactory;

    public function setType(?ILineupPlayerTypeEntity $lineupPlayerType): ILineupPlayerEntityFactory;

    public function setPositionX(int $positionX): ILineupPlayerEntityFactory;

    public function setPositionY(int $positionY): ILineupPlayerEntityFactory;

    public function setShirtNumber(int $shirtNumber): ILineupPlayerEntityFactory;

    public function setHomeTeam(bool $homeTeam): ILineupPlayerEntityFactory;

    public function create(): ILineupPlayerEntity;

    public function createFromArray(array $data): ILineupPlayerEntity;
}