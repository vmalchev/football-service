<?php


namespace Sportal\FootballApi\Infrastructure\MatchEvent;

use DateTimeImmutable;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntity;
use Sportal\FootballApi\Domain\MatchEvent\MatchEventType;
use Sportal\FootballApi\Domain\MatchEvent\TeamPositionStatus;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class MatchEventEntity extends GeneratedIdDatabaseEntity implements IMatchEventEntity, IDatabaseEntity
{
    private ?string $id;

    private string $matchId;

    private MatchEventType $eventType;

    private TeamPositionStatus $teamPosition;

    private int $minute;

    private ?string $teamId;

    private ?IPlayerEntity $primaryPlayer;

    private ?string $primaryPlayerId;

    private ?IPlayerEntity $secondaryPlayer;

    private ?string $secondaryPlayerId;

    private ?int $goalHome;

    private ?int $goalAway;

    private ?int $sortOrder;

    /**
     * MatchEventEntity constructor.
     * @param string|null $id
     * @param string $matchId
     * @param MatchEventType $eventType
     * @param TeamPositionStatus $teamPosition
     * @param int $minute
     * @param string|null $teamId
     * @param IPlayerEntity|null $primaryPlayer
     * @param string|null $primaryPlayerId
     * @param IPlayerEntity|null $secondaryPlayer
     * @param string|null $secondaryPlayerId
     * @param int|null $goalHome
     * @param int|null $goalAway
     * @param int|null $sortOrder
     */
    public function __construct(?string $id,
                                string $matchId,
                                MatchEventType $eventType,
                                TeamPositionStatus $teamPosition,
                                int $minute,
                                ?string $teamId,
                                ?IPlayerEntity $primaryPlayer,
                                ?string $primaryPlayerId,
                                ?IPlayerEntity $secondaryPlayer,
                                ?string $secondaryPlayerId,
                                ?int $goalHome,
                                ?int $goalAway,
                                ?int $sortOrder)
    {
        $this->id = $id;
        $this->matchId = $matchId;
        $this->eventType = $eventType;
        $this->teamPosition = $teamPosition;
        $this->minute = $minute;
        $this->teamId = $teamId;
        $this->primaryPlayer = $primaryPlayer;
        $this->primaryPlayerId = $primaryPlayerId;
        $this->secondaryPlayer = $secondaryPlayer;
        $this->secondaryPlayerId = $secondaryPlayerId;
        $this->goalHome = $goalHome;
        $this->goalAway = $goalAway;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }

    /**
     * @return MatchEventType
     */
    public function getEventType(): MatchEventType
    {
        return $this->eventType;
    }

    /**
     * @return TeamPositionStatus
     */
    public function getTeamPosition(): TeamPositionStatus
    {
        return $this->teamPosition;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    /**
     * @return IPlayerEntity|null
     */
    public function getPrimaryPlayer(): ?IPlayerEntity
    {
        return $this->primaryPlayer;
    }

    /**
     * @return string|null
     */
    public function getPrimaryPlayerId(): ?string
    {
        return $this->primaryPlayerId;
    }

    /**
     * @return IPlayerEntity|null
     */
    public function getSecondaryPlayer(): ?IPlayerEntity
    {
        return $this->secondaryPlayer;
    }

    /**
     * @return string|null
     */
    public function getSecondaryPlayerId(): ?string
    {
        return $this->secondaryPlayerId;
    }

    /**
     * @return int|null
     */
    public function getGoalHome(): ?int
    {
        return $this->goalHome;
    }

    /**
     * @return int|null
     */
    public function getGoalAway(): ?int
    {
        return $this->goalAway;
    }

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }


    public function getDatabaseEntry(): array
    {
        return [
            MatchEventTableMapper::FIELD_EVENT_ID => $this->matchId,
            MatchEventTableMapper::FIELD_TYPE => $this->eventType->getValue(),
            MatchEventTableMapper::FIELD_HOME_TEAM => TeamPositionStatusConverter::toValue($this->teamPosition),
            MatchEventTableMapper::FIELD_MINUTE => $this->minute,
            MatchEventTableMapper::FIELD_PLAYER_ID => $this->primaryPlayerId,
            MatchEventTableMapper::FIELD_PLAYER_NAME => $this->primaryPlayer !== null ? $this->primaryPlayer->getName() : null,
            MatchEventTableMapper::FIELD_REL_PLAYER_ID => $this->secondaryPlayerId,
            MatchEventTableMapper::FIELD_REL_PLAYER_NAME => $this->secondaryPlayer !== null ? $this->secondaryPlayer->getName() : null,
            MatchEventTableMapper::FIELD_GOAL_HOME => $this->goalHome,
            MatchEventTableMapper::FIELD_GOAL_AWAY => $this->goalAway,
            MatchEventTableMapper::FIELD_SORT_ORDER => (int)$this->sortOrder,
            // This is added to preserve legacy softDelete logic
            MatchEventTableMapper::FIELD_DELETED => false,
            MatchEventTableMapper::FIELD_UPDATED_AT => new DateTimeImmutable()
        ];
    }

    /**
     * @inheritDoc
     */
    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $entity = clone $this;
        $entity->id = $id;
        return $entity;
    }
}