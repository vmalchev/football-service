<?php


namespace Sportal\FootballApi\Domain\Standing;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class StandingModel implements IStandingModel
{
    private IStandingProfile $standingProfile;

    private ?IBlacklistKey $blacklistKey = null;

    private IStandingRepository $standingRepository;

    private IStandingEntryRepository $standingEntryRepository;

    private ITransactionManager $transactionManager;

    private IBlacklistKeyFactory $blacklistFactory;

    private IBlacklistRepository $blacklistRepository;

    /**
     * StandingModel constructor.
     * @param IStandingRepository $standingRepository
     * @param IStandingEntryRepository $standingEntryRepository
     * @param ITransactionManager $transactionManager
     * @param IBlacklistKeyFactory $blacklistFactory
     * @param IBlacklistRepository $blacklistRepository
     */
    public function __construct(IStandingRepository $standingRepository,
                                IStandingEntryRepository $standingEntryRepository,
                                ITransactionManager $transactionManager,
                                IBlacklistKeyFactory $blacklistFactory,
                                IBlacklistRepository $blacklistRepository)
    {
        $this->standingRepository = $standingRepository;
        $this->standingEntryRepository = $standingEntryRepository;
        $this->transactionManager = $transactionManager;
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistRepository = $blacklistRepository;
    }


    public function setStanding(IStandingProfile $standing): IStandingModel
    {
        $model = clone $this;
        $model->standingProfile = $standing;
        return $model;
    }

    public function getStanding(): IStandingProfile
    {
        return $this->standingProfile;
    }

    public function withBlacklist(): IStandingModel
    {
        $standingEntity = $this->standingProfile->getStandingEntity();
        $entityName = new BlacklistEntityName($standingEntity->getEntityName()->getValue());
        $model = clone $this;
        $model->blacklistKey = $this->blacklistFactory->setEmpty()
            ->setEntity($entityName)
            ->setType(BlacklistType::RELATION())
            ->setEntityId($standingEntity->getEntityId())
            ->setContext($standingEntity->getType()->getValue())
            ->create();
        return $model;
    }

    public function upsert(): IStandingModel
    {
        return $this->transactionManager->transactional(function () {
            $standingEntity = $this->standingRepository->upsert($this->standingProfile->getStandingEntity());
            $standingEntries = $this->standingEntryRepository->upsert($standingEntity, $this->standingProfile->getEntries());
            if ($this->blacklistKey !== null) {
                $this->blacklistRepository->upsert($this->blacklistKey);
            }
            return $this->setStanding($this->standingProfile->setStandingEntity($standingEntity)->setEntries($standingEntries));
        });
    }
}