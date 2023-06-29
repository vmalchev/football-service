<?php


namespace Sportal\FootballApi\Application\Stage\Output\Get;


use Sportal\FootballApi\Domain\Stage\IStageEntity;

class Mapper
{
    public function map(?IStageEntity $stageEntity): ?Dto
    {
        if ($stageEntity === null) {
            return null;
        }
        return new Dto($stageEntity->getId(),
            $stageEntity->getName(),
            $stageEntity->getType(),
            $stageEntity->getStartDate() !== null ? $stageEntity->getStartDate()->format('Y-m-d') : null,
            $stageEntity->getEndDate() !== null ? $stageEntity->getEndDate()->format('Y-m-d') : null,
            $stageEntity->getOrderInSeason(),
            $stageEntity->getCoverage() !== null ? $stageEntity->getCoverage()->getKey() : null,
            !is_null($stageEntity->getStageStatus()) ? $stageEntity->getStageStatus()->getKey() : null
        );
    }

}