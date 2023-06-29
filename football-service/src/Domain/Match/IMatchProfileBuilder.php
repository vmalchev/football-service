<?php


namespace Sportal\FootballApi\Domain\Match;


interface IMatchProfileBuilder
{
    public function build(IMatchEntity $matchEntity): IMatchProfile;
}