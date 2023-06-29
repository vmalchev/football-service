<?php


namespace Sportal\FootballApi\Domain\Stage;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class StageModel implements IStageModel
{

    private IStageRepository $stageRepository;

    private IBlacklistKeyFactory $blacklistFactory;

    private IBlacklistRepository $blacklistRepository;

    private ITransactionManager $transactionManager;

    private array $stageEntities;

    private bool $hasBlacklist = false;

    public function __construct(IStageRepository $stageRepository,
                                IBlacklistKeyFactory $blacklistFactory,
                                IBlacklistRepository $blacklistRepository,
                                ITransactionManager $transactionManager)
    {
        $this->stageRepository = $stageRepository;
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->transactionManager = $transactionManager;
    }

    public function setStages(array $stageEntities): IStageModel
    {
        $model = clone $this;
        $model->stageEntities = $stageEntities;
        return $model;
    }

    public function getStages(): array
    {
        return $this->stageEntities;
    }

    public function withBlacklist(): IStageModel
    {
        $model = clone $this;
        $model->hasBlacklist = true;
        return $model;
    }

    public function upsert(): IStageModel {
        return $this->transactionManager->transactional(function () {
            $stageEntities = [];

            foreach ($this->stageEntities as $stageEntity) {
                if ($stageEntity->getId() !== null) {
                    $stageEntities[] = $this->update($stageEntity);
                } else {
                    $stageEntities[] = $this->create($stageEntity);
                }
            }

            usort($stageEntities, function ($item1, $item2) {
                return $item1->getOrderInSeason() <=> $item2->getOrderInSeason();
            });

            return $this->setStages($stageEntities);
        });
    }

    public function delete(): void
    {
        $this->transactionManager->transactional(function () {
            foreach ($this->stageEntities as $stageEntity) {
                $this->stageRepository->delete($stageEntity->getId());

                if ($this->hasBlacklist) {
                    $this->deleteBlacklist($stageEntity);
                }
            }
        });
    }

    private function create(IStageEntity $stageEntity): IStageEntity
    {
        $insertedStageEntity = $this->stageRepository->insert($stageEntity);

        if ($this->hasBlacklist) {
            $this->upsertBlacklist($insertedStageEntity);
        }

        return $insertedStageEntity;
    }

    private function update(IStageEntity $stageEntity): IStageEntity
    {
        $this->stageRepository->update($stageEntity);

        if ($this->hasBlacklist) {
            $this->upsertBlacklist($stageEntity);
        }

        return $stageEntity;
    }

    private function upsertBlacklist(IStageEntity $seasonEntity): void
    {
        $blacklistKey = $this->blacklistFactory
            ->setType(BlacklistType::ENTITY())
            ->setEntity(BlacklistEntityName::STAGE())
            ->setEntityId($seasonEntity->getId())
            ->create();
        $this->blacklistRepository->upsert($blacklistKey);
    }

    private function deleteBlacklist(IStageEntity $seasonEntity): void
    {
        $blacklistKey = $this->blacklistFactory
            ->setType(BlacklistType::ENTITY())
            ->setEntity(BlacklistEntityName::STAGE())
            ->setEntityId($seasonEntity->getId())
            ->create();
        $this->blacklistRepository->deleteAll([$blacklistKey]);
    }

}