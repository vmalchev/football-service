<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamCoachEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamCoachEntityFactory;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;

class TeamCoachEntityFactory implements ITeamCoachEntityFactory
{
    private string $teamId;
    private string $coachId;
    private TeamSquadStatus $status;
    private ?ICoachEntity $coach = null;
    private ?DateTimeInterface $startDate = null;
    private ?DateTimeInterface $endDate = null;


    public function setEmpty(): ITeamCoachEntityFactory
    {
        return new TeamCoachEntityFactory();
    }

    /**
     * @param string $teamId
     * @return TeamCoachEntityFactory
     */
    public function setTeamId(string $teamId): TeamCoachEntityFactory
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param string $coachId
     * @return TeamCoachEntityFactory
     */
    public function setCoachId(string $coachId): TeamCoachEntityFactory
    {
        $this->coachId = $coachId;
        return $this;
    }

    /**
     * @param TeamSquadStatus $status
     * @return TeamCoachEntityFactory
     */
    public function setStatus(TeamSquadStatus $status): TeamCoachEntityFactory
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param ICoachEntity|null $coach
     * @return TeamCoachEntityFactory
     */
    public function setCoach(?ICoachEntity $coach): TeamCoachEntityFactory
    {
        $this->coach = $coach;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $startDate
     * @return TeamCoachEntityFactory
     */
    public function setStartDate(?DateTimeInterface $startDate): TeamCoachEntityFactory
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $endDate
     * @return TeamCoachEntityFactory
     */
    public function setEndDate(?DateTimeInterface $endDate): TeamCoachEntityFactory
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function setFrom(ITeamCoachEntity $entity): ITeamCoachEntityFactory
    {
        $factory = new TeamCoachEntityFactory();
        $factory->teamId = $entity->getTeamId();
        $factory->coachId = $entity->getCoachId();
        $factory->startDate = $entity->getStartDate();
        $factory->endDate = $entity->getEndDate();
        $factory->status = $entity->getStatus();
        $factory->coach = $entity->getCoach();
        return $factory;
    }


    public function create(): TeamCoachEntity
    {
        return new TeamCoachEntity(
            null,
            $this->teamId,
            $this->coachId,
            $this->status,
            $this->coach,
            $this->startDate,
            $this->endDate
        );
    }
}