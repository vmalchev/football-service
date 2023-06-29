<?php


namespace Sportal\FootballApi\Application\Group\Output\Get;


use Sportal\FootballApi\Domain\Group\IGroupEntity;

class Mapper
{
    public function map(?IGroupEntity $group): ?Dto
    {
        if ($group === null) {
            return null;
        }

        return new Dto(
            $group->getId(),
            $group->getName(),
            $group->getSortorder()
        );
    }
}