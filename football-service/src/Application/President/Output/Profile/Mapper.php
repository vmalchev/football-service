<?php


namespace Sportal\FootballApi\Application\President\Output\Profile;


use Sportal\FootballApi\Domain\President\IPresidentEntity;

class Mapper
{
    public function map(?IPresidentEntity $presidentEntity): ?Dto
    {
        if (is_null($presidentEntity)) {
            return null;
        }

        return new Dto(
            $presidentEntity->getId(),
            $presidentEntity->getName()
        );
    }
}