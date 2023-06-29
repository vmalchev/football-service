<?php


namespace Sportal\FootballApi\Domain\Group;


use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\Stage\IStageEntity;

class GroupRule implements IGroupRule
{

    private IGroupRepository $groupRepository;

    public function __construct(IGroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    /**
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function validate(IStageEntity $stageEntity, array $groups)
    {
        $updatedGroups = $this->groupRepository->findByStageId($stageEntity->getId());

        $existingIds = array_map(fn($group) => $group->getId(), $updatedGroups);

        foreach ($groups as $group) {
            if (is_null($group->getId())) {
                $updatedGroups[] = $group;
            } elseif (in_array($group->getId(), $existingIds)) {
                $position = array_search($group->getId(), array_map(fn($group) => $group->getId(), $updatedGroups));
                $updatedGroups[$position] = $group;
            } else {
                throw new NoSuchEntityException('Group ' . $group->getId());
            }
        }

        $nameArray = array_map(fn($item) => $item->getName(), $updatedGroups);
        if (array_unique($nameArray) !== $nameArray) {
            $duplicateNames = array_diff_assoc($nameArray, array_unique($nameArray));
            throw new DuplicateKeyException(
                'Group with name ' . implode(',', array_unique($duplicateNames)) . ' already exists'
            );
        }

        $orderArray = array_map(fn($item) => $item->getSortorder(), $updatedGroups);
        if (array_unique($orderArray) !== $orderArray) {
            $duplicateOrder = array_diff_assoc($orderArray, array_unique($orderArray));
            throw new DuplicateKeyException(
                'Group with order_in_stage ' . implode(',', array_unique($duplicateOrder)) . ' already exists'
            );
        }
    }

}