<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;

interface IPlayerSeasonStatisticEntity
{
    public function getPlayerId(): string;
    /**
     * @return IPlayerEntity|null
     */
    public function getPlayerEntity(): ?IPlayerEntity;

    /**
     * @return string
     */
    public function getSeasonId(): string;

    /**
     * @return ISeasonEntity|null
     */
    public function getSeasonEntity(): ?ISeasonEntity;

    public function getTeamIds();

    /**
     * @return mixed
     */
    public function getTeamEntities();

    /**
     * @return IPlayerStatisticItem[]
     */
    public function getStatisticItems(): array;
}