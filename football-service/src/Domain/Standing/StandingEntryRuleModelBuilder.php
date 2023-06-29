<?php


namespace Sportal\FootballApi\Domain\Standing;


use Sportal\FootballApi\Domain\Standing\Exception\InvalidStandingException;
use Sportal\FootballApi\Domain\StandingRule\IStandingRuleRepository;

class StandingEntryRuleModelBuilder
{
    private IStandingRuleRepository $standingRuleRepository;

    private IStandingEntryRuleFactory $standingEntryRuleFactory;

    private IStandingEntryRuleModel $entryRuleModel;

    /**
     * StandingEntryRuleModelBuilder constructor.
     * @param IStandingRuleRepository $standingRuleRepository
     * @param IStandingEntryRuleFactory $standingEntryRuleFactory
     * @param IStandingEntryRuleModel $entryRuleModel
     */
    public function __construct(IStandingRuleRepository $standingRuleRepository,
                                IStandingEntryRuleFactory $standingEntryRuleFactory,
                                IStandingEntryRuleModel $entryRuleModel)
    {
        $this->standingRuleRepository = $standingRuleRepository;
        $this->standingEntryRuleFactory = $standingEntryRuleFactory;
        $this->entryRuleModel = $entryRuleModel;
    }

    /**
     * @param IStandingProfile $standing
     * @return IStandingEntryRuleModel
     * @throws InvalidStandingException
     */
    public function build(IStandingProfile $standing): IStandingEntryRuleModel
    {
        if (!$standing->getStandingEntity()->getType()->equals(StandingType::LEAGUE())) {
            throw new InvalidStandingException("Standing must be of type LEAGUE");
        }

        $uniqueEntries = array_unique(array_map(fn($entryRule) => "{$entryRule->getStandingRuleId()}-{$entryRule->getRank()}", $standing->getEntryRules()));
        if (count($uniqueEntries) != count($standing->getEntryRules())) {
            throw new InvalidStandingException("Standing rules contains duplicate standing_rule_id,rank");
        }

        foreach ($standing->getEntryRules() as $entryRule) {
            if (!$this->standingRuleRepository->existsById($entryRule->getStandingRuleId())) {
                throw new InvalidStandingException("StandingRule {$entryRule->getStandingRuleId()} is not valid");
            }
        }
        return $this->entryRuleModel->setStanding($standing);
    }

}