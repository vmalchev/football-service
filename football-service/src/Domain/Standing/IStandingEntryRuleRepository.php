<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingEntryRuleRepository
{
    /**
     * @param IStandingEntity $standingEntity
     * @param IStandingEntryRule[] $standingEntryRules
     * @return IStandingEntryRule[]
     */
    public function upsertByStanding(IStandingEntity $standingEntity, array $standingEntryRules): array;
}