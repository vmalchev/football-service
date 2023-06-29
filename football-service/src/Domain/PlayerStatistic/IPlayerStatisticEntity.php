<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface IPlayerStatisticEntity
{
    /**
     * @return string
     */
    public function getPlayerId(): string;

    /**
     * @return IPlayerEntity|null
     */
    public function getPlayerEntity(): ?IPlayerEntity;

    /**
     * @return string
     */
    public function getMatchId(): string;

    /**
     * @return IMatchEntity|null
     */
    public function getMatchEntity(): ?IMatchEntity;

    /**
     * @return string
     */
    public function getTeamId(): string;

    /**
     * @return ITeamEntity|null
     */
    public function getTeamEntity(): ?ITeamEntity;

    /**
     * @return IPlayerStatisticItem[]
     */
    public function getStatisticItems(): array;

    /**
     * @return OriginType
     */
    public function getOrigin(): OriginType;
}