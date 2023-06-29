<?php


namespace Sportal\FootballApi\Application\Group\Service;


use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Domain\Group\IGroupDeleteRule;
use Sportal\FootballApi\Domain\Group\IGroupModel;
use Sportal\FootballApi\Domain\Group\IGroupRepository;

class Destroy implements IService
{

    private IGroupRepository $groupRepository;

    private IGroupDeleteRule $groupDeleteRule;

    private IGroupModel $groupModel;

    public function __construct(IGroupRepository $groupRepository,
                                IGroupDeleteRule $groupDeleteRule,
                                IGroupModel $groupModel)
    {
        $this->groupRepository = $groupRepository;
        $this->groupDeleteRule = $groupDeleteRule;
        $this->groupModel = $groupModel;
    }

    /**
     * @param IDto $inputDto
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto)
    {
        $groupId = $inputDto->getId();
        $groupEntity = $this->groupRepository->findById($groupId);

        if (is_null($groupEntity)) {
            throw new NoSuchEntityException('Group ' . $groupId);
        }

        $this->groupDeleteRule->validate($groupEntity);

        $this->groupModel
            ->setGroups([$groupEntity])
            ->withBlacklist()
            ->delete();
    }

}