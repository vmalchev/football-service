<?php


namespace Sportal\FootballApi\Domain\Match;


interface IMatchModel
{
    public function setMatch(IMatchEntity $matchEntity): IMatchModel;

    public function getMatch(): IMatchEntity;

    public function withBlacklist(): IMatchModel;

    public function create(): IMatchModel;

    public function update(): IMatchModel;

}