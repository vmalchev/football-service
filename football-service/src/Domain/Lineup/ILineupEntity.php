<?php


namespace Sportal\FootballApi\Domain\Lineup;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;

interface ILineupEntity
{
    public function getMatchId(): string;

    public function getStatus(): ?LineupStatus;

    public function getHomeTeamFormation(): ?string;

    public function getAwayTeamFormation(): ?string;

    public function getHomeCoachId(): ?string;

    public function getAwayCoachId(): ?string;

    public function getHomeCoach(): ?ICoachEntity;

    public function getAwayCoach(): ?ICoachEntity;

    public function getHomeTeamId(): ?string;

    public function getAwayTeamId(): ?string;
}