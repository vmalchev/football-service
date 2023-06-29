<?php


namespace Sportal\FootballApi\Domain\Match;


interface IMatchModelBuilder
{
    public function build(IMatchEntity $matchEntity): IMatchModel;
}