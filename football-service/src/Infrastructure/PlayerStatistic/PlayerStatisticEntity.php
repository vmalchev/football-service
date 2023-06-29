<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\OriginType;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class PlayerStatisticEntity implements IPlayerStatisticEntity, IDatabaseEntity
{
    private string $playerId;
    private ?IPlayerEntity $playerEntity;

    private string $matchId;
    private ?IMatchEntity $matchEntity;

    private string $teamId;
    private ?ITeamEntity $teamEntity;

    /**
     * @var PlayerStatisticItem[]
     */
    private array $statisticItems;

    private OriginType $origin;

    /**
     * @param string $playerId
     * @param IPlayerEntity|null $playerEntity
     * @param string $matchId
     * @param IMatchEntity|null $matchEntity
     * @param string $teamId
     * @param ITeamEntity|null $teamEntity
     * @param array $statisticItems
     * @param OriginType $origin
     */
    public function __construct(
        string $playerId,
        ?IPlayerEntity $playerEntity,
        string $matchId,
        ?IMatchEntity $matchEntity,
        string $teamId,
        ?ITeamEntity $teamEntity,
        array $statisticItems,
        OriginType $origin
    ) {
        $this->playerId = $playerId;
        $this->playerEntity = $playerEntity;
        $this->matchId = $matchId;
        $this->matchEntity = $matchEntity;
        $this->teamId = $teamId;
        $this->teamEntity = $teamEntity;
        $this->statisticItems = $statisticItems;
        $this->origin = $origin;
    }

    /**
     * @return string
     */
    public function getPlayerId(): string
    {
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
    public function getMatchId(): string
    {
        return $this->matchId;
    }

    /**
     * @return IMatchEntity|null
     */
    public function getMatchEntity(): ?IMatchEntity
    {
        return $this->matchEntity;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * @return ITeamEntity|null
     */
    public function getTeamEntity(): ?ITeamEntity
    {
        return $this->teamEntity;
    }

    /**
     * @return PlayerStatisticItem[]
     */
    public function getStatisticItems(): array
    {
        return $this->statisticItems;
    }

    /**
     * @return OriginType
     */
    public function getOrigin(): OriginType
    {
        return $this->origin;
    }

    public function getDatabaseEntry(): array
    {
        return [
            PlayerStatisticTable::FIELD_MATCH_ID => $this->getMatchId(),
            PlayerStatisticTable::FIELD_PLAYER_ID => $this->getPlayerId(),
            PlayerStatisticTable::FIELD_TEAM_ID => $this->getTeamId(),
            PlayerStatisticTable::FIELD_PLAYER_STATISTIC_ITEM => json_encode($this->getStatisticItems()),
            PlayerStatisticTable::FIELD_ORIGIN => $this->getOrigin(),
            PlayerStatisticTable::FIELD_UPDATED_AT => new \DateTime()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            PlayerStatisticTable::FIELD_MATCH_ID => $this->getMatchId(),
            PlayerStatisticTable::FIELD_PLAYER_ID => $this->getPlayerId(),
            PlayerStatisticTable::FIELD_ORIGIN => $this->getOrigin(),
        ];
    }
}