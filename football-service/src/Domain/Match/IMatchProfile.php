<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;

interface IMatchProfile
{
    public function setMatch(IMatchEntity $matchEntity): IMatchProfile;

    public function getMatch(): IMatchEntity;

    public function getMinute(): ?MatchMinute;

    public function getTeamColors(): ?ITeamColorsEntity;
}