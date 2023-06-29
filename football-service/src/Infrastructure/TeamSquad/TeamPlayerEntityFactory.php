<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntityFactory;
use Sportal\FootballApi\Domain\TeamSquad\PlayerContractType;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class TeamPlayerEntityFactory implements ITeamPlayerEntityFactory
{
    private ?string $id = null;

    private string $teamId;

    private string $playerId;

    private ?IPlayerEntity $player = null;

    private TeamSquadStatus $status;

    private PlayerContractType $contractType;

    private ?DateTimeInterface $startDate = null;

    private ?DateTimeInterface $endDate = null;

    private ?int $shirtNumber = null;

    private ?ITeamEntity $team = null;

    public function setEmpty(): ITeamPlayerEntityFactory
    {
        return new TeamPlayerEntityFactory();
    }

    public function setFrom(ITeamPlayerEntity $entity): ITeamPlayerEntityFactory
    {
        $factory = new TeamPlayerEntityFactory();
        $factory->id = $entity->getId();
        $factory->teamId = $entity->getTeamId();
        $factory->playerId = $entity->getPlayerId();
        $factory->status = $entity->getStatus();
        $factory->contractType = $entity->getContractType();
        $factory->startDate = $entity->getStartDate();
        $factory->endDate = $entity->getEndDate();
        $factory->shirtNumber = $entity->getShirtNumber();
        $factory->team = $entity->getTeam();
        return $factory;
    }

    /**
     * @param string $teamId
     * @return TeamPlayerEntityFactory
     */
    public function setTeamId(string $teamId): TeamPlayerEntityFactory
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param string $playerId
     * @return TeamPlayerEntityFactory
     */
    public function setPlayerId(string $playerId): TeamPlayerEntityFactory
    {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * @param ?IPlayerEntity $player
     * @return TeamPlayerEntityFactory
     */
    public function setPlayer(?IPlayerEntity $player): TeamPlayerEntityFactory
    {
        $this->player = $player;
        return $this;
    }

    /**
     * @param TeamSquadStatus $status
     * @return TeamPlayerEntityFactory
     */
    public function setStatus(TeamSquadStatus $status): TeamPlayerEntityFactory
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param PlayerContractType $contractType
     * @return TeamPlayerEntityFactory
     */
    public function setContractType(PlayerContractType $contractType): TeamPlayerEntityFactory
    {
        $this->contractType = $contractType;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $startDate
     * @return TeamPlayerEntityFactory
     */
    public function setStartDate(?DateTimeInterface $startDate): TeamPlayerEntityFactory
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $endDate
     * @return TeamPlayerEntityFactory
     */
    public function setEndDate(?DateTimeInterface $endDate): TeamPlayerEntityFactory
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @param int|null $shirtNumber
     * @return TeamPlayerEntityFactory
     */
    public function setShirtNumber(?int $shirtNumber): TeamPlayerEntityFactory
    {
        $this->shirtNumber = $shirtNumber;
        return $this;
    }

    /**
     * @param ITeamEntity|null $teamEntity
     * @return ITeamPlayerEntityFactory
     */
    public function setTeam(?ITeamEntity $teamEntity): ITeamPlayerEntityFactory
    {
        $this->team = $teamEntity;
        return $this;
    }

    public function create(): TeamPlayerEntity
    {
        return new TeamPlayerEntity(
            $this->id,
            $this->teamId,
            $this->playerId,
            $this->player,
            $this->status,
            $this->contractType,
            $this->startDate,
            $this->endDate,
            $this->shirtNumber,
            $this->team
        );
    }

    /**
     * @param string|null $id
     * @return ITeamPlayerEntityFactory
     */
    public function setId(?string $id): ITeamPlayerEntityFactory
    {
        $this->id = $id;
        return $this;
    }
}