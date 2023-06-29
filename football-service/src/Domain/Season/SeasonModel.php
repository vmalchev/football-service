<?php


namespace Sportal\FootballApi\Domain\Season;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class SeasonModel implements ISeasonModel
{

    private ISeasonRepository $seasonRepository;

    private IBlacklistKeyFactory $blacklistFactory;

    private IBlacklistRepository $blacklistRepository;

    private ITransactionManager $transactionManager;

    private ISeasonEntity $seasonEntity;

    private ISeasonEntityFactory $seasonEntityFactory;

    private bool $hasBlacklist = false;

    public function __construct(ISeasonRepository $seasonRepository,
                                IBlacklistKeyFactory $blacklistFactory,
                                IBlacklistRepository $blacklistRepository,
                                ITransactionManager $transactionManager,
                                ISeasonEntityFactory $seasonEntityFactory)
    {
        $this->seasonRepository = $seasonRepository;
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->transactionManager = $transactionManager;
        $this->seasonEntityFactory = $seasonEntityFactory;
    }

    public function setSeason(ISeasonEntity $seasonEntity): ISeasonModel
    {
        $model = clone $this;
        $model->seasonEntity = $seasonEntity;
        return $model;
    }

    public function getSeason(): ISeasonEntity
    {
        return $this->seasonEntity;
    }

    public function withBlacklist(): ISeasonModel
    {
        $model = clone $this;
        $model->hasBlacklist = true;
        return $model;
    }

    public function create(): ISeasonModel
    {
        return $this->transactionManager->transactional(function () {
            $insertedSeason = $this->seasonRepository->insert($this->seasonEntity);
            if ($this->hasBlacklist) {
                $this->upsertBlacklist($insertedSeason);
            }
            return $this->setSeason($insertedSeason);
        });
    }

    public function update(): ISeasonModel
    {
        $this->transactionManager->transactional(function () {
            $this->seasonRepository->update($this->seasonEntity);
            if ($this->hasBlacklist) {
                $this->upsertBlacklist($this->seasonEntity);
            }
        });
        return $this;
    }

    public function updateStatus(): ISeasonModel
    {
        $this->transactionManager->transactional(function () {
            $filter = SeasonFilter::create()
                ->setTournamentId($this->getSeason()->getTournamentId())
                ->setStatus(SeasonStatus::ACTIVE());

            $seasons = $this->seasonRepository->listByFilter($filter);

            foreach ($seasons as $season) {
                $entity = $this->seasonEntityFactory
                    ->setFrom($season)
                    ->setStatus(SeasonStatus::INACTIVE())
                    ->create();
                $this->seasonRepository->update($entity);
                if ($this->hasBlacklist) {
                    $this->upsertBlacklist($entity);
                }
            }

            $this->seasonRepository->update($this->getSeason());
            if ($this->hasBlacklist) {
                $this->upsertBlacklist($this->getSeason());
           }
        });
        return $this;
    }

    private function upsertBlacklist(ISeasonEntity $seasonEntity): void
    {
        $blacklistKey = $this->blacklistFactory->setType(BlacklistType::ENTITY())
            ->setEntity(BlacklistEntityName::SEASON())
            ->setEntityId($seasonEntity->getId())
            ->create();
        $this->blacklistRepository->upsert($blacklistKey);
    }
}