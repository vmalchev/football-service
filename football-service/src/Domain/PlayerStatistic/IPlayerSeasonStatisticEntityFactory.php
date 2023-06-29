<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Team\ITeamCollection;

interface IPlayerSeasonStatisticEntityFactory
{
    /**
     * @param IPlayerSeasonStatisticEntity $playerStatisticEntity
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function setEntity(IPlayerSeasonStatisticEntity $playerStatisticEntity): IPlayerSeasonStatisticEntityFactory;

    /**
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function getPlayerStatisticFactory(): IPlayerSeasonStatisticEntityFactory;

    /**
     * @param $id
     * @return $this
     */
    public function setPlayerId($id): self;

    /**
     * @param IPlayerEntity $playerEntity
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function setPlayerEntity(IPlayerEntity $playerEntity): self;

    /**
     * @param string $seasonId
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function setSeasonId(string $seasonId): self;

    /**
     * @param ISeasonEntity $seasonEntity
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function setSeasonEntity(ISeasonEntity $seasonEntity): self;

    /**
     * @param $id
     * @return $this
     */
    public function setTeamIds($id): self;

    /**
     * @param ITeamCollection|null $teams
     * @return $this
     */
    public function setTeamEntities(?ITeamCollection $teams): self;

    /**
     * @param IPlayerStatisticItem[] $statistics
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function setStatistics(array $statistics): self;

    /**
     * @return IPlayerSeasonStatisticEntity
     */
    public function create(): IPlayerSeasonStatisticEntity;
}