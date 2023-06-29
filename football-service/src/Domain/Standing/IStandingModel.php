<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingModel
{
    public function setStanding(IStandingProfile $standing): IStandingModel;

    public function getStanding(): IStandingProfile;

    public function withBlacklist(): IStandingModel;

    public function upsert(): IStandingModel;
}