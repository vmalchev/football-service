<?php


namespace Sportal\FootballApi\Application\StandingEntryRule\Input\Put;


use Sportal\FootballApi\Domain\Standing\IStandingEntity;
use Sportal\FootballApi\Domain\Standing\IStandingEntryRuleFactory;
use Sportal\FootballApi\Domain\Standing\IStandingProfile;

class Mapper
{
    private IStandingEntryRuleFactory $factory;
    private IStandingProfile $standing;

    /**
     * Mapper constructor.
     * @param IStandingEntryRuleFactory $factory
     * @param IStandingProfile $standing
     */
    public function __construct(IStandingEntryRuleFactory $factory, IStandingProfile $standing)
    {
        $this->factory = $factory;
        $this->standing = $standing;
    }

    /**
     * @param IStandingEntity $standingEntity
     * @param EntryRuleDto[] $rules
     * @return IStandingProfile
     */
    public function map(IStandingEntity $standingEntity, array $rules): IStandingProfile
    {
        $entryRules = array_map(fn($ruleDto) => $this->factory->setEmpty()->setRank($ruleDto->getRank())
            ->setStandingRuleId($ruleDto->getStandingRuleId())
            ->setStandingId($standingEntity->getId())
            ->create(), $rules);
        return $this->standing->setStandingEntity($standingEntity)->setEntryRules($entryRules);
    }

}