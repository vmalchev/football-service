<?php


namespace Sportal\FootballApi\Domain\Group;


use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface IGroupRule
{

    /**
     * @param IStageEntity $stageEntity
     * @param IGroupEntity[] $groups
     */
    public function validate(IStageEntity $stageEntity, array $groups);

}