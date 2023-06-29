<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateConverter;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Team\TeamEntity;

class TeamPlayerEntity extends GeneratedIdDatabaseEntity implements ITeamPlayerEntity
{
    private ?string $id;

    private string $teamId;

    private string $playerId;

    private ?IPlayerEntity $player;

    private TeamSquadStatus $status;

    private PlayerContractType $contractType;

    private ?DateTimeInterface $startDate;

    private ?DateTimeInterface $endDate;

    private ?int $shirtNumber;

    private ?ITeamEntity $team;

    /**
     * TeamPlayerEntity constructor.
     * @param string|null $id
     * @param string $teamId
     * @param string $playerId
     * @param IPlayerEntity|null $player
     * @param TeamSquadStatus $status
     * @param PlayerContractType $contractType
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @param int|null $shirtNumber
     * @param ITeamEntity|null $team
     */
    public function __construct(?string $id,
                                string $teamId,
                                string $playerId,
                                ?IPlayerEntity $player,
                                TeamSquadStatus $status,
                                PlayerContractType $contractType,
                                ?DateTimeInterface $startDate = null,
                                ?DateTimeInterface $endDate = null,
                                ?int $shirtNumber = null,
                                ?ITeamEntity $team = null)
    {
        $this->id = $id;
        $this->teamId = $teamId;
        $this->playerId = $playerId;
        $this->player = $player;
        $this->status = $status;
        $this->contractType = $contractType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->shirtNumber = $shirtNumber;
        $this->team = $team;
    }



    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    /**
     * @return IPlayerEntity
     */
    public function getPlayer(): ?IPlayerEntity
    {
        return $this->player;
    }

    /**
     * @return TeamSquadStatus
     */
    public function getStatus(): TeamSquadStatus
    {
        return $this->status;
    }

    /**
     * @return PlayerContractType
     */
    public function getContractType(): PlayerContractType
    {
        return $this->contractType;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @return int|null
     */
    public function getShirtNumber(): ?int
    {
        return $this->shirtNumber;
    }

    /**
     * @return ITeamEntity|null
     */
    public function getTeam(): ?ITeamEntity
    {
        return $this->team;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): TeamPlayerEntity
    {
        $teamPlayer = clone $this;
        $teamPlayer->id = $id;
        return $teamPlayer;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TeamPlayerTableMapper::FIELD_PLAYER_ID => $this->playerId,
            TeamPlayerTableMapper::FIELD_TEAM_ID => $this->teamId,
            TeamPlayerTableMapper::FIELD_ACTIVE => StatusDatabaseConverter::toValue($this->status),
            TeamPlayerTableMapper::FIELD_LOAN => ContractTypeDatabaseConverter::toValue($this->contractType),
            TeamPlayerTableMapper::FIELD_START_DATE => DateConverter::toValue($this->startDate),
            TeamPlayerTableMapper::FIELD_END_DATE => DateConverter::toValue($this->endDate),
            TeamPlayerTableMapper::FIELD_SHIRT_NUMBER => $this->shirtNumber
        ];
    }

}