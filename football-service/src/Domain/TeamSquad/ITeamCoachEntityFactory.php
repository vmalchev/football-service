<?php

namespace Sportal\FootballApi\Domain\TeamSquad;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Infrastructure\TeamSquad\TeamCoachEntityFactory;

interface ITeamCoachEntityFactory
{
    public function setEmpty(): ITeamCoachEntityFactory;

    public function setFrom(ITeamCoachEntity $entity): ITeamCoachEntityFactory;

    /**
     * @param string $teamId
     * @return TeamCoachEntityFactory
     */
    public function setTeamId(string $teamId): TeamCoachEntityFactory;

    /**
     * @param string $coachId
     * @return TeamCoachEntityFactory
     */
    public function setCoachId(string $coachId): TeamCoachEntityFactory;

    /**
     * @param TeamSquadStatus $status
     * @return TeamCoachEntityFactory
     */
    public function setStatus(TeamSquadStatus $status): TeamCoachEntityFactory;

    /**
     * @param ICoachEntity|null $coach
     * @return TeamCoachEntityFactory
     */
    public function setCoach(?ICoachEntity $coach): TeamCoachEntityFactory;

    /**
     * @param DateTimeInterface|null $startDate
     * @return TeamCoachEntityFactory
     */
    public function setStartDate(?DateTimeInterface $startDate): TeamCoachEntityFactory;

    /**
     * @param DateTimeInterface|null $endDate
     * @return TeamCoachEntityFactory
     */
    public function setEndDate(?DateTimeInterface $endDate): TeamCoachEntityFactory;

    public function create(): ITeamCoachEntity;
}