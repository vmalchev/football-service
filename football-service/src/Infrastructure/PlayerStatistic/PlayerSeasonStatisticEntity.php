<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticEntity;
use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Domain\Team\ITeamCollection;

class PlayerSeasonStatisticEntity implements IPlayerSeasonStatisticEntity
{
    private string $playerId;
    private ?IPlayerEntity $playerEntity;

    private string $seasonId;
    private ?ISeasonEntity $seasonEntity;

    private array $teamIds;
    private ?ITeamCollection $teamEntities;

    /**
     * @var PlayerStatisticItem[]
     */
    private array $statisticItems;

    /**
     * @param string $playerId
     * @param IPlayerEntity|null $playerEntity
     * @param string $seasonId
     * @param ISeasonEntity|null $seasonEntity
     * @param array $teamIds
     * @param ITeamCollection|null $teamEntities
     * @param array $statisticItems
     */
    public function __construct(
        string $playerId,
        ?IPlayerEntity $playerEntity,
        string $seasonId,
        ?ISeasonEntity $seasonEntity,
        array $teamIds,
        ?ITeamCollection $teamEntities,
        array $statisticItems
    ) {
        $this->playerId = $playerId;
        $this->playerEntity = $playerEntity;
        $this->seasonId = $seasonId;
        $this->seasonEntity = $seasonEntity;
        $this->teamIds = $teamIds;
        $this->teamEntities = $teamEntities;
        $this->statisticItems = $statisticItems;
    }

    public function getPlayerId(): string {
        return $this->playerId;
    }

    /**
     * @return IPlayerEntity|null
     */
    public function getPlayerEntity(): ?IPlayerEntity
    {
        return $this->playerEntity;
    }

    /**
     * @return string
     */
    public function getSeasonId(): string
    {
        return $this->seasonId;
    }

    /**
     * @return ISeasonEntity|null
     */
    public function getSeasonEntity(): ?ISeasonEntity
    {
        return $this->seasonEntity;
    }

    public function getTeamIds(): array
    {
        return $this->teamIds;
    }

    public function getTeamEntities(): ?ITeamCollection
    {
        return $this->teamEntities;
    }

    /**
     * @return PlayerStatisticItem[]
     */
    public function getStatisticItems(): array
    {
        return $this->statisticItems;
    }
}