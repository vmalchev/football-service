<?php

namespace Sportal\FootballApi\Infrastructure\Lineup;


use Sportal\FootballApi\Domain\Lineup\ILineupPlayerEntity;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class LineupPlayerEntity extends GeneratedIdDatabaseEntity implements ILineupPlayerEntity
{
    private ?string $id;
    private string $matchId;
    private ?string $playerName;
    private ?int $lineupPlayerTypeId;
    private ?ILineupPlayerTypeEntity $lineupPlayerType;
    private ?string $playerId;
    private ?IPlayerEntity $player;
    private ?int $positionX;
    private ?int $positionY;
    private ?int $shirtNumber;
    private bool $homeTeam;

    /**
     * LineupPlayerEntity constructor.
     * @param string|null $id
     * @param string $matchId
     * @param string|null $playerName
     * @param string|null $lineupPlayerTypeId
     * @param ILineupPlayerTypeEntity|null $lineupPlayerType
     * @param string|null $playerId
     * @param IPlayerEntity|null $player
     * @param int|null $positionX
     * @param int|null $positionY
     * @param int|null $shirtNumber
     * @param bool $homeTeam
     */
    public function __construct(?string $id,
                                string $matchId,
                                ?string $playerName,
                                ?string $lineupPlayerTypeId,
                                ?ILineupPlayerTypeEntity $lineupPlayerType,
                                ?string $playerId,
                                ?IPlayerEntity $player,
                                ?int $positionX,
                                ?int $positionY,
                                ?int $shirtNumber,
                                bool $homeTeam)
    {
        $this->id = $id;
        $this->matchId = $matchId;
        $this->playerName = $playerName;
        $this->lineupPlayerTypeId = $lineupPlayerTypeId;
        $this->lineupPlayerType = $lineupPlayerType;
        $this->playerId = $playerId;
        $this->player = $player;
        $this->positionX = $positionX;
        $this->positionY = $positionY;
        $this->shirtNumber = $shirtNumber;
        $this->homeTeam = $homeTeam;
    }

    /**
     * @return string|null
     */
    public function getTypeId(): ?string
    {
        return $this->lineupPlayerTypeId;
    }

    public function getType(): ?ILineupPlayerTypeEntity
    {
        return $this->lineupPlayerType;
    }

    /**
     * @return string|null
     */
    public function getPlayerId(): ?string
    {
        return $this->playerId;
    }

    /**
     * @return IPlayerEntity|null
     */
    public function getPlayer(): ?IPlayerEntity
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getPlayerName(): ?string
    {
        return $this->playerName;
    }

    /**
     * @return int|null
     */
    public function getPositionX(): ?int
    {
        return $this->positionX;
    }

    /**
     * @return int|null
     */
    public function getPositionY(): ?int
    {
        return $this->positionY;
    }

    /**
     * @return int|null
     */
    public function getShirtNumber(): ?int
    {
        return $this->shirtNumber;
    }

    /**
     * @return bool|null
     */
    public function getHomeTeam(): bool
    {
        return $this->homeTeam;
    }

    /**
     * @return string|null
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }

    public function getDatabaseEntry(): array
    {
        return [
            LineupPlayerTable::FIELD_MATCH_ID => $this->getMatchId(),
            LineupPlayerTable::FIELD_PLAYER_NAME => $this->getPlayerName(),
            LineupPlayerTable::FIELD_TYPE_ID => $this->getTypeId(),
            LineupPlayerTable::FIELD_PLAYER_ID => $this->getPlayerId(),
            LineupPlayerTable::FIELD_POSITION_X => $this->getPositionX(),
            LineupPlayerTable::FIELD_POSITION_Y => $this->getPositionY(),
            LineupPlayerTable::FIELD_SHIRT_NUMBER => $this->getShirtNumber(),
            LineupPlayerTable::FIELD_HOME_TEAM => $this->getHomeTeam(),
        ];
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $updated = clone $this;
        $updated->playerId = $id;
        return $updated;
    }
}