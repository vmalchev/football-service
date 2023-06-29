<?php


namespace Sportal\FootballApi\Domain\Match;


interface IMatchScore
{
    public function getTotal(): ITeamScore;

    public function getHalfTime(): ?ITeamScore;

    public function getRegularTime(): ?ITeamScore;

    public function getExtraTime(): ?ITeamScore;

    public function getPenaltyShootout(): ?ITeamScore;

    public function getAggregate(): ?ITeamScore;
}