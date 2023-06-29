<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface IPlayerStatisticEntityFactory
{
    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticEntityFactory
     */
    public function setEntity(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticEntityFactory;

    /**
     * @return IPlayerStatisticEntityFactory
     */
    public function getPlayerStatisticFactory(): IPlayerStatisticEntityFactory;

    /**
     * @param string $playerId
     * @return IPlayerStatisticEntityFactory
     */
    public function setPlayerId(string $playerId): self;

    /**
     * @param IPlayerEntity $playerEntity
     * @return IPlayerStatisticEntityFactory
     */
    public function setPlayerEntity(IPlayerEntity $playerEntity): self;

    /**
     * @param string $matchId
     * @return IPlayerStatisticEntityFactory
     */
    public function setMatchId(string $matchId): self;

    /**
     * @param IMatchEntity $matchEntity
     * @return IPlayerStatisticEntityFactory
     */
    public function setMatchEntity(IMatchEntity $matchEntity): self;

    /**
     * @param string $teamId
     * @return IPlayerStatisticEntityFactory
     */
    public function setTeamId(string $teamId): self;

    /**
     * @param ITeamEntity $teamEntity
     * @return IPlayerStatisticEntityFactory
     */
    public function setTeamEntity(ITeamEntity $teamEntity): self;

    /**
     * @param IPlayerStatisticItem[] $statistics
     * @return IPlayerStatisticEntityFactory
     */
    public function setStatistics(array $statistics): self;

    /**
     * @param OriginType $origin
     * @return IPlayerStatisticEntityFactory
     */
    public function setOrigin(OriginType $origin): self;

    /**
     * @return IPlayerStatisticEntity
     */
    public function create(): IPlayerStatisticEntity;
}