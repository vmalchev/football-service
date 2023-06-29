<?php


namespace Sportal\FootballApi\Domain\Standing;


interface ITopScorerEntry extends IStandingEntry
{
    public function getGoals(): int;

    public function getPlayed(): ?int;

    public function getAssists(): ?int;

    public function getScoredFirst(): ?int;

    public function getMinutes(): ?int;

    public function getPenalties(): ?int;

    public function getYellowCards(): ?int;

}