<?php


namespace Sportal\FootballApi\Infrastructure\MatchEvent;


use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntity;
use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntityFactory;
use Sportal\FootballApi\Domain\MatchEvent\MatchEventType;
use Sportal\FootballApi\Domain\MatchEvent\TeamPositionStatus;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;

class MatchEventEntityFactory implements IMatchEventEntityFactory
{
    private ?string $id = null;

    private string $matchId;

    private MatchEventType $eventType;

    private TeamPositionStatus $teamPosition;

    private int $minute;

    private ?string $teamId = null;

    private ?IPlayerEntity $primaryPlayer = null;

    private ?string $primaryPlayerId = null;

    private ?IPlayerEntity $secondaryPlayer = null;

    private ?string $secondaryPlayerId = null;

    private ?int $goalHome = null;

    private ?int $goalAway = null;

    private ?int $sortOrder = null;

    public function setFrom(IMatchEventEntity $entity): IMatchEventEntityFactory
    {
        $factory = new MatchEventEntityFactory();
        $factory->id = $entity->getId();
        $factory->matchId = $entity->getMatchId();
        $factory->eventType = $entity->getEventType();
        $factory->teamPosition = $entity->getTeamPosition();
        $factory->minute = $entity->getMinute();
        $this->teamId = $entity->getTeamId();
        $factory->primaryPlayer = $entity->getPrimaryPlayer();
        $factory->primaryPlayerId = $entity->getPrimaryPlayerId();
        $factory->secondaryPlayer = $entity->getSecondaryPlayer();
        $factory->secondaryPlayerId = $entity->getSecondaryPlayerId();
        $factory->goalHome = $entity->getGoalHome();
        $factory->goalAway = $entity->getGoalAway();
        $factory->sortOrder = $entity->getSortOrder();
        return $factory;
    }

    public function setEmpty(): IMatchEventEntityFactory
    {
        return new MatchEventEntityFactory();
    }

    /**
     * @param string|null $id
     * @return MatchEventEntityFactory
     */
    public function setId(?string $id): MatchEventEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $matchId
     * @return MatchEventEntityFactory
     */
    public function setMatchId(string $matchId): MatchEventEntityFactory
    {
        $this->matchId = $matchId;
        return $this;
    }

    /**
     * @param MatchEventType $eventType
     * @return MatchEventEntityFactory
     */
    public function setEventType(MatchEventType $eventType): MatchEventEntityFactory
    {
        $this->eventType = $eventType;
        return $this;
    }

    /**
     * @param TeamPositionStatus $teamPosition
     * @return MatchEventEntityFactory
     */
    public function setTeamPosition(TeamPositionStatus $teamPosition): MatchEventEntityFactory
    {
        $this->teamPosition = $teamPosition;
        return $this;
    }

    /**
     * @param int $minute
     * @return MatchEventEntityFactory
     */
    public function setMinute(int $minute): MatchEventEntityFactory
    {
        $this->minute = $minute;
        return $this;
    }

    /**
     * @param string|null $teamId
     * @return MatchEventEntityFactory
     */
    public function setTeamId(?string $teamId): MatchEventEntityFactory
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param IPlayerEntity|null $primaryPlayer
     * @return MatchEventEntityFactory
     */
    public function setPrimaryPlayer(?IPlayerEntity $primaryPlayer): MatchEventEntityFactory
    {
        $this->primaryPlayer = $primaryPlayer;
        return $this;
    }

    /**
     * @param string|null $primaryPlayerId
     * @return MatchEventEntityFactory
     */
    public function setPrimaryPlayerId(?string $primaryPlayerId): MatchEventEntityFactory
    {
        $this->primaryPlayerId = $primaryPlayerId;
        return $this;
    }

    /**
     * @param IPlayerEntity|null $secondaryPlayer
     * @return MatchEventEntityFactory
     */
    public function setSecondaryPlayer(?IPlayerEntity $secondaryPlayer): MatchEventEntityFactory
    {
        $this->secondaryPlayer = $secondaryPlayer;
        return $this;
    }

    /**
     * @param string|null $secondaryPlayerId
     * @return MatchEventEntityFactory
     */
    public function setSecondaryPlayerId(?string $secondaryPlayerId): MatchEventEntityFactory
    {
        $this->secondaryPlayerId = $secondaryPlayerId;
        return $this;
    }

    /**
     * @param int|null $goalHome
     * @return MatchEventEntityFactory
     */
    public function setGoalHome(?int $goalHome): MatchEventEntityFactory
    {
        $this->goalHome = $goalHome;
        return $this;
    }

    /**
     * @param int|null $goalAway
     * @return MatchEventEntityFactory
     */
    public function setGoalAway(?int $goalAway): MatchEventEntityFactory
    {
        $this->goalAway = $goalAway;
        return $this;
    }

    /**
     * @param int|null $sortOrder
     * @return MatchEventEntityFactory
     */
    public function setSortOrder(?int $sortOrder): MatchEventEntityFactory
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function create(): MatchEventEntity
    {
        return new MatchEventEntity(
            $this->id,
            $this->matchId,
            $this->eventType,
            $this->teamPosition,
            $this->minute,
            $this->teamId,
            $this->primaryPlayer,
            $this->primaryPlayerId,
            $this->secondaryPlayer,
            $this->secondaryPlayerId,
            $this->goalHome,
            $this->goalAway,
            $this->sortOrder,
        );
    }

}