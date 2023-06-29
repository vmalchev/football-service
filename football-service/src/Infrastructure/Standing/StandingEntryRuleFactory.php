<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\IStandingEntryRule;
use Sportal\FootballApi\Domain\Standing\IStandingEntryRuleFactory;

class StandingEntryRuleFactory implements IStandingEntryRuleFactory
{
    private string $standingId;

    private string $standingRuleId;

    private int $rank;

    public function setEmpty(): IStandingEntryRuleFactory
    {
        return new StandingEntryRuleFactory();
    }

    /**
     * @param string $standingId
     * @return IStandingEntryRuleFactory
     */
    public function setStandingId(string $standingId): IStandingEntryRuleFactory
    {
        $this->standingId = $standingId;
        return $this;
    }

    /**
     * @param string $standingRuleId
     * @return IStandingEntryRuleFactory
     */
    public function setStandingRuleId(string $standingRuleId): IStandingEntryRuleFactory
    {
        $this->standingRuleId = $standingRuleId;
        return $this;
    }

    /**
     * @param int $rank
     * @return IStandingEntryRuleFactory
     */
    public function setRank(int $rank): IStandingEntryRuleFactory
    {
        $this->rank = $rank;
        return $this;
    }

    public function create(): StandingEntryRule
    {
        return new StandingEntryRule($this->standingId, $this->standingRuleId, $this->rank);
    }

    public function setFrom(IStandingEntryRule $entryRule): IStandingEntryRuleFactory
    {
        $factory = new StandingEntryRuleFactory();
        $factory->rank = $entryRule->getRank();
        $factory->standingRuleId = $entryRule->getStandingRuleId();
        $factory->standingId = $entryRule->getStandingId();
        return $factory;
    }
}