<?php


namespace Sportal\FootballApi\Domain\Group;


use Sportal\FootballApi\Domain\Blacklist\BlacklistEntityName;
use Sportal\FootballApi\Domain\Blacklist\BlacklistType;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKeyFactory;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Domain\Database\ITransactionManager;

class GroupModel implements IGroupModel
{

    private IGroupRepository $groupRepository;

    private IBlacklistKeyFactory $blacklistKeyFactory;

    private IBlacklistRepository $blacklistRepository;

    private ITransactionManager $transactionManager;

    private array $groupEntities;

    private bool $hasBlacklist = false;

    public function __construct(IBlacklistKeyFactory $blacklistKeyFactory,
                                IBlacklistRepository $blacklistRepository,
                                ITransactionManager $transactionManager,
                                IGroupRepository $groupRepository)
    {
        $this->blacklistKeyFactory = $blacklistKeyFactory;
        $this->blacklistRepository = $blacklistRepository;
        $this->transactionManager = $transactionManager;
        $this->groupRepository = $groupRepository;
    }

    public function setGroups(array $groupEntities): IGroupModel
    {
        $model = clone $this;
        $model->groupEntities = $groupEntities;
        return $model;
    }

    public function getGroups(): array
    {
        return $this->groupEntities;
    }

    public function withBlacklist(): IGroupModel
    {
        $model = clone $this;
        $model->hasBlacklist = true;
        return $model;
    }

    public function upsert(): IGroupModel
    {
        return $this->transactionManager->transactional(function () {
           $groups = [];

           foreach ($this->groupEntities as $groupEntity) {
               if ($groupEntity->getId() !== null) {
                   $groups[] = $this->update($groupEntity);
               } else {
                   $groups[] = $this->create($groupEntity);
               }
           }

           usort($groups, function ($item1, $item2) {
               return $item1->getSortorder() <=> $item2->getSortorder();
           });

           return $this->setGroups($groups);
        });
    }

    public function delete()
    {
        $this->transactionManager->transactional(function () {
            foreach ($this->groupEntities as $groupEntity) {
                $this->groupRepository->delete($groupEntity->getId());

                if ($this->hasBlacklist) {
                    $this->deleteBlacklist($groupEntity);
                }
            }
        });
    }

    private function create(IGroupEntity $groupEntity): IGroupEntity
    {
        $insertedGroup = $this->groupRepository->create($groupEntity);

        if ($this->hasBlacklist) {
            $this->upsertBlacklist($insertedGroup);
        }

        return $insertedGroup;
    }

    private function update(IGroupEntity $groupEntity): IGroupEntity
    {
        $this->groupRepository->update($groupEntity);

        if ($this->hasBlacklist) {
            $this->upsertBlacklist($groupEntity);
        }

        return $groupEntity;
    }

    private function upsertBlacklist(IGroupEntity $groupEntity) {
        $blacklistKey = $this->blacklistKeyFactory->setType(BlacklistType::ENTITY())
            ->setEntity(BlacklistEntityName::GROUP())
            ->setEntityId($groupEntity->getId())
            ->create();
        $this->blacklistRepository->upsert($blacklistKey);
    }

    private function deleteBlacklist(IGroupEntity $groupEntity) {
        $blacklistKey = $this->blacklistKeyFactory->setType(BlacklistType::ENTITY())
            ->setEntity(BlacklistEntityName::GROUP())
            ->setEntityId($groupEntity->getId())
            ->create();
        $this->blacklistRepository->deleteAll([$blacklistKey]);
    }

}