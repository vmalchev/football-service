<?php

namespace Sportal\FootballApi\Infrastructure\Lineup;


use Sportal\FootballApi\Domain\Lineup\ILineupPlayerEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupPlayerEntityFactory;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;

class LineupPlayerEntityFactory implements ILineupPlayerEntityFactory
{
    private string $id;
    private string $matchId;
    private ?string $playerName = null;
    private ?string $lineupPlayerTypeId;
    private ?ILineupPlayerTypeEntity $lineupPlayerType = null;
    private ?string $playerId;
    private ?IPlayerEntity $player = null;
    private ?int $positionX;
    private ?int $positionY;
    private ?int $shirtNumber;
    private bool $homeTeam;

    public function setEntity(ILineupPlayerEntity $lineupPlayerEntity): ILineupPlayerEntityFactory
    {
        $factory = new LineupPlayerEntityFactory();

        $factory->setMatchId($lineupPlayerEntity->getMatchId());
        $factory->setPlayerName($lineupPlayerEntity->getPlayerName());
        $factory->setTypeId($lineupPlayerEntity->getTypeId());
        $factory->setType($lineupPlayerEntity->getType());
        $factory->setPlayerId($lineupPlayerEntity->getPlayerId());
        $factory->setPlayer($lineupPlayerEntity->getPlayer());
        $factory->setPositionX($lineupPlayerEntity->getPositionX());
        $factory->setPositionY($lineupPlayerEntity->getPositionY());
        $factory->setShirtNumber($lineupPlayerEntity->getShirtNumber());
        $factory->setHomeTeam($lineupPlayerEntity->getHomeTeam());

        return $factory;
    }

    public function setEmpty(): ILineupPlayerEntityFactory
    {
        return new LineupPlayerEntityFactory();
    }

    public function setType(?\Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity $lineupPlayerType): ILineupPlayerEntityFactory
    {
        $this->lineupPlayerType = $lineupPlayerType;
        return $this;
    }

    public function setMatchId(string $matchId): ILineupPlayerEntityFactory
    {
        $this->matchId = $matchId;
        return $this;
    }

    public function setPlayerName(?string $playerName): ILineupPlayerEntityFactory
    {
        $this->playerName = $playerName;
        return $this;
    }

    public function setTypeId(string $lineupPlayerTypeId): ILineupPlayerEntityFactory
    {
        $this->lineupPlayerTypeId = $lineupPlayerTypeId;
        return $this;
    }

    public function setPlayerId(?string $playerId): ILineupPlayerEntityFactory
    {
        $this->playerId = $playerId;
        return $this;
    }

    public function setPlayer(?IPlayerEntity $player): ILineupPlayerEntityFactory
    {
        $this->player = $player;
        return $this;
    }

    public function setPositionX(?int $positionX): ILineupPlayerEntityFactory
    {
        $this->positionX = $positionX;
        return $this;
    }

    public function setPositionY(?int $positionY): ILineupPlayerEntityFactory
    {
        $this->positionY = $positionY;
        return $this;
    }

    public function setShirtNumber(?int $shirtNumber): ILineupPlayerEntityFactory
    {
        $this->shirtNumber = $shirtNumber;
        return $this;
    }

    public function setHomeTeam(bool $homeTeam): ILineupPlayerEntityFactory
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    public function create(): ILineupPlayerEntity
    {
        return new LineupPlayerEntity(
            !empty($this->id) ? $this->id : null,
            $this->matchId,
            isset($this->playerName) ? $this->playerName : null,
            $this->lineupPlayerTypeId,
            $this->lineupPlayerType,
            $this->playerId,
            $this->player,
            $this->positionX,
            $this->positionY,
            $this->shirtNumber,
            $this->homeTeam
        );
    }

    public function createFromArray(array $data): ILineupPlayerEntity
    {
        $factory = new LineupPlayerEntityFactory();

        $factory->id = $data[LineupPlayerTable::FIELD_ID];
        $factory->matchId = $data[LineupPlayerTable::FIELD_MATCH_ID];
        $factory->playerName = $data[LineupPlayerTable::FIELD_PLAYER_NAME];
        $factory->lineupPlayerTypeId = $data[LineupPlayerTable::FIELD_TYPE_ID];
        $factory->lineupPlayerType = isset($data[LineupPlayerTable::FIELD_TYPE]) ? $data[LineupPlayerTable::FIELD_TYPE] : null;
        $factory->playerId = isset($data[LineupPlayerTable::FIELD_PLAYER_ID]) ? $data[LineupPlayerTable::FIELD_PLAYER_ID] : null;
        $factory->player = isset($data[LineupPlayerTable::FIELD_PLAYER]) ? $data[LineupPlayerTable::FIELD_PLAYER] : null;
        $factory->positionX = isset($data[LineupPlayerTable::FIELD_POSITION_X]) ? $data[LineupPlayerTable::FIELD_POSITION_X] : null;
        $factory->positionY = isset($data[LineupPlayerTable::FIELD_POSITION_Y]) ? $data[LineupPlayerTable::FIELD_POSITION_Y] : null;
        $factory->shirtNumber = isset($data[LineupPlayerTable::FIELD_SHIRT_NUMBER]) ? $data[LineupPlayerTable::FIELD_SHIRT_NUMBER] : null;
        $factory->homeTeam = isset($data[LineupPlayerTable::FIELD_HOME_TEAM]) ? $data[LineupPlayerTable::FIELD_HOME_TEAM] : true;

        return $factory->create();
    }

}