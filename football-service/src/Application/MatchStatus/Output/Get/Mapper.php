<?php


namespace Sportal\FootballApi\Application\MatchStatus\Output\Get;


use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;

class Mapper
{
    public function map(?IMatchStatusEntity $status): ?Dto
    {
        if ($status === null) {
            return null;
        }

        return new Dto(
            $status->getId(),
            $status->getName(),
            $status->getShortName(),
            $status->getType()->getKey(),
            $status->getCode(),
        );
    }
}