<?php

namespace Sportal\FootballApi\Domain\TeamSquad;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;

interface ITeamCoachEntity
{
    /**
     * @return string
     */
    public function getTeamId(): string;

    /**
     * @return string
     */
    public function getCoachId(): string;

    /**
     * @return TeamSquadStatus
     */
    public function getStatus(): TeamSquadStatus;

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface;

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface;

    /**
     * @return ICoachEntity|null
     */
    public function getCoach(): ?ICoachEntity;
}