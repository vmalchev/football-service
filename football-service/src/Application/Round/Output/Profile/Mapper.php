<?php

namespace Sportal\FootballApi\Application\Round\Output\Profile;

use Sportal\FootballApi\Domain\Round\IRoundEntity;

class Mapper
{

    public function map(?IRoundEntity $roundEntity): ?Dto
    {
        if ($roundEntity === null) {
            return null;
        }

        $roundType = $roundEntity->getType() == null ? null : $roundEntity->getType()->getValue();

        return new Dto(
            $roundEntity->getId(),
            $roundEntity->getKey(),
            $roundEntity->getName(),
            $roundType,
            !is_null($roundEntity->getStartDate()) ? $roundEntity->getStartDate()->format('Y-m-d') : null,
            !is_null($roundEntity->getEndDate()) ? $roundEntity->getEndDate()->format('Y-m-d') : null,
            $roundEntity->getRoundStatus()
        );
    }
}