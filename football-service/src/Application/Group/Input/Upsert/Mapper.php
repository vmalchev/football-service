<?php


namespace Sportal\FootballApi\Application\Group\Input\Upsert;


use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\Group\IGroupEntityFactory;

class Mapper
{

    private IGroupEntityFactory $groupEntityFactory;

    public function __construct(IGroupEntityFactory $groupEntityFactory)
    {
        $this->groupEntityFactory = $groupEntityFactory;
    }

    /**
     * @param CollectionDto $collectionDto
     * @return IGroupEntity[]
     */
    public function map(CollectionDto $collectionDto): array
    {
        $groups = $collectionDto->getGroups();

        $groupEntities = [];
        foreach ($groups as $group) {
            $groupEntities[] = $this->groupEntityFactory
                ->setId($group->getId())
                ->setName($group->getName())
                ->setSortorder($group->getOrderInStage())
                ->setStageId($collectionDto->getStageId())
                ->create();
        }

        return $groupEntities;
    }

}