<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingEntry
{
    public function getTeamId(): string;

    public function getRank(): int;

    public function getPlayerId(): ?string;
}