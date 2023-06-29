<?php


namespace Sportal\FootballApi\Domain\Standing;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class StandingEntryRuleModel implements IStandingEntryRuleModel
{
    private IStandingProfile $standing;

    private ?IBlacklistKey $blacklistKey = null;

    private IBlacklistKeyFactory $blacklistFactory;

    private IBlacklistRepository $blacklistRepository;

    private IStandingEntryRuleRepository $entryRuleRepository;

    private ITransactionManager $transactionManager;

    /**
     * StandingEntryRuleModel constructor.
     * @param IBlacklistKeyFactory $blacklistFactory
     * @param IBlacklistRepository $blacklistRepository
     * @param IStandingEntryRuleRepository $entryRuleRepository
     * @param ITransactionManager $transactionManager
     */
    public function __construct(IBlacklistKeyFactory $blacklistFactory,
                                IBlacklistRepository $blacklistRepository,
                                IStandingEntryRuleRepository $entryRuleRepository,
                                ITransactionManager $transactionManager)
    {
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->entryRuleRepository = $entryRuleRepository;
        $this->transactionManager = $transactionManager;
    }


    public function setStanding(IStandingProfile $standing): IStandingEntryRuleModel
    {
        $model = clone $this;
        $model->standing = $standing;
        return $model;
    }


    public function withBlacklist(): IStandingEntryRuleModel
    {
        $standingEntity = $this->standing->getStandingEntity();
        $entityName = new BlacklistEntityName($standingEntity->getEntityName()->getValue());
        $model = clone $this;
        $model->blacklistKey = $this->blacklistFactory->setEmpty()
            ->setEntity($entityName)
            ->setType(BlacklistType::RELATION())
            ->setEntityId($standingEntity->getEntityId())
            ->setContext('league_standing_rules')
            ->create();
        return $model;
    }

    public function upsert(): void
    {
        $this->transactionManager->transactional(function () {
            $this->entryRuleRepository->upsertByStanding($this->standing->getStandingEntity(), $this->standing->getEntryRules());
            if ($this->blacklistKey !== null) {
                $this->blacklistRepository->upsert($this->blacklistKey);
            }
        });
    }
}