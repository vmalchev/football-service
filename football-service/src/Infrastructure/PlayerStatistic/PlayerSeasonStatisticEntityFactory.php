<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticEntityFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItem;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Team\ITeamCollection;

class PlayerSeasonStatisticEntityFactory implements IPlayerSeasonStatisticEntityFactory
{
    private string $playerId;
    private ?IPlayerEntity $playerEntity = null;

    private string $seasonId;
    private ?ISeasonEntity $seasonEntity = null;

    private array $teamIds;
    private ?ITeamCollection $teamEntities = null;

    private array $statistics;

    public function setEntity(IPlayerSeasonStatisticEntity $playerStatisticEntity): IPlayerSeasonStatisticEntityFactory
    {
        return (clone $this)
            ->setPlayerId($playerStatisticEntity->getPlayerId())
            ->setTeamIds($playerStatisticEntity->getTeamIds())
            ->setSeasonId($playerStatisticEntity->getSeasonId())
            ->setSeasonEntity($playerStatisticEntity->getSeasonEntity())
            ->setTeamEntities($playerStatisticEntity->getTeamEntities())
            ->setPlayerEntity($playerStatisticEntity->getPlayerEntity())
            ->setStatistics($playerStatisticEntity->getStatisticItems());
    }

    /**
     * @return IPlayerSeasonStatisticEntityFactory
     */
    public function getPlayerStatisticFactory(): IPlayerSeasonStatisticEntityFactory
    {
        return clone $this;
    }

    public function setPlayerId($id): self {
        $this->playerId = $id;
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
     * @param string $seasonId
     * @return PlayerSeasonStatisticEntityFactory
     */
    public function setSeasonId(string $seasonId): self
    {
        $this->seasonId = $seasonId;
        return $this;
    }

    /**
     * @param ISeasonEntity|null $seasonEntity
     * @return $this
     */
    public function setSeasonEntity(?ISeasonEntity $seasonEntity): self
    {
        $this->seasonEntity = $seasonEntity;
        return $this;
    }

    public function setTeamIds($id): self {
        $this->teamIds = $id;
        return $this;
    }

    public function setTeamEntities(?ITeamCollection $teams): self {
        $this->teamEntities = $teams;
        return $this;
    }

    /**
     * @param IPlayerStatisticItem[] $statistics
     * @return PlayerSeasonStatisticEntityFactory
     */
    public function setStatistics(array $statistics): self
    {
        $this->statistics = $statistics;
        return $this;
    }

    /**
     * @return IPlayerSeasonStatisticEntity
     */
    public function create(): IPlayerSeasonStatisticEntity
    {
        return new PlayerSeasonStatisticEntity(
            $this->playerId,
            $this->playerEntity,
            $this->seasonId,
            $this->seasonEntity,
            $this->teamIds,
            $this->teamEntities,
            $this->statistics
        );
    }
}