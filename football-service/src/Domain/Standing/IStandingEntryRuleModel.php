<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingEntryRuleModel
{
    public function setStanding(IStandingProfile $standing): IStandingEntryRuleModel;

    public function withBlacklist(): IStandingEntryRuleModel;

    public function upsert(): void;
}