<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntityFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItem;
use Sportal\FootballApi\Domain\PlayerStatistic\OriginType;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

class PlayerStatisticEntityFactory implements IPlayerStatisticEntityFactory
{
    private string $playerId;
    private ?IPlayerEntity $playerEntity = null;

    private string $matchId;
    private ?IMatchEntity $matchEntity = null;

    private string $teamId;
    private ?ITeamEntity $teamEntity = null;

    private array $statistics;

    private OriginType $origin;

    public function setEntity(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticEntityFactory
    {
        return (clone $this)
            ->setMatchId($playerStatisticEntity->getMatchId())
            ->setMatchEntity($playerStatisticEntity->getMatchEntity())
            ->setTeamId($playerStatisticEntity->getTeamId())
            ->setTeamEntity($playerStatisticEntity->getTeamEntity())
            ->setPlayerId($playerStatisticEntity->getPlayerId())
            ->setPlayerEntity($playerStatisticEntity->getPlayerEntity())
            ->setStatistics($playerStatisticEntity->getStatisticItems())
            ->setOrigin($playerStatisticEntity->getOrigin());
    }

    /**
     * @return IPlayerStatisticEntityFactory
     */
    public function getPlayerStatisticFactory(): IPlayerStatisticEntityFactory
    {
        return clone $this;
    }

    /**
     * @param string $playerId
     * @return PlayerStatisticEntityFactory
     */
    public function setPlayerId(string $playerId): self
    {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * @param IPlayerEntity|null $playerEntity
     * @return $this
     */
    public function setPlayerEntity(?IPlayerEntity $playerEntity): self
    {
        $this->playerEntity = $playerEntity;
        return $this;
    }

    /**
     * @param string $matchId
     * @return PlayerStatisticEntityFactory
     */
    public function setMatchId(string $matchId): self
    {
        $this->matchId = $matchId;
        return $this;
    }

    /**
     * @param IMatchEntity|null $matchEntity
     * @return $this
     */
    public function setMatchEntity(?IMatchEntity $matchEntity): self
    {
        $this->matchEntity = $matchEntity;
        return $this;
    }

    /**
     * @param string $teamId
     * @return PlayerStatisticEntityFactory
     */
    public function setTeamId(string $teamId): self
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param ITeamEntity|null $teamEntity
     * @return $this
     */
    public function setTeamEntity(?ITeamEntity $teamEntity): self
    {
        $this->teamEntity = $teamEntity;
        return $this;
    }

    /**
     * @param IPlayerStatisticItem[] $statistics
     * @return PlayerStatisticEntityFactory
     */
    public function setStatistics(array $statistics): self
    {
        $this->statistics = $statistics;
        return $this;
    }

    /**
     * @param OriginType $origin
     * @return PlayerStatisticEntityFactory
     */
    public function setOrigin(OriginType $origin): self
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * @return IPlayerStatisticEntity
     */
    public function create(): IPlayerStatisticEntity
    {
        return new PlayerStatisticEntity(
            $this->playerId,
            $this->playerEntity,
            $this->matchId,
            $this->matchEntity,
            $this->teamId,
            $this->teamEntity,
            $this->statistics,
            $this->origin
        );
    }

    public function createFromArray(array $data): IPlayerStatisticEntity
    {
        return (clone $this)
            ->setPlayerId($data[PlayerStatisticTable::FIELD_PLAYER_ID])
            ->setPlayerEntity($data[PlayerStatisticTable::FIELD_PLAYER_ENTITY] ?? null)
            ->setMatchId($data[PlayerStatisticTable::FIELD_MATCH_ID])
            ->setMatchEntity($data[PlayerStatisticTable::FIELD_MATCH_ENTITY] ?? null)
            ->setTeamId($data[PlayerStatisticTable::FIELD_TEAM_ID])
            ->setTeamEntity($data[PlayerStatisticTable::FIELD_MATCH_ENTITY] ?? null)
            ->setStatistics(json_decode($data[PlayerStatisticTable::FIELD_PLAYER_STATISTIC_ITEM]))
            ->setOrigin(new OriginType($data[PlayerStatisticTable::FIELD_ORIGIN]))
            ->create();
    }
}