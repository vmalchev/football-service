<?php

namespace Sportal\FootballApi\Domain\Standing;

interface IStandingEntryRuleFactory
{
    public function setEmpty(): IStandingEntryRuleFactory;

    public function setFrom(IStandingEntryRule $entryRule): IStandingEntryRuleFactory;

    /**
     * @param string $standingId
     * @return IStandingEntryRuleFactory
     */
    public function setStandingId(string $standingId): IStandingEntryRuleFactory;

    /**
     * @param string $standingRuleId
     * @return IStandingEntryRuleFactory
     */
    public function setStandingRuleId(string $standingRuleId): IStandingEntryRuleFactory;

    /**
     * @param int $rank
     * @return IStandingEntryRuleFactory
     */
    public function setRank(int $rank): IStandingEntryRuleFactory;

    public function create(): IStandingEntryRule;
}