<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Referee;


use Sportal\FootballApi\Domain\Match\IMatchRefereeEntity;

class Mapper
{
    /**
     * @param IMatchRefereeEntity[] $matchReferees
     * @return Dto[]
     */
    public function map(?array $matchReferees): ?array
    {
        if (empty($matchReferees)) {
            return null;
        }

        return array_map(fn(IMatchRefereeEntity $referee) => new Dto(
            $referee->getRefereeId(),
            $referee->getRefereeName(),
            $referee->getRole()->getKey(),
            $referee->getGender()
        ), $matchReferees);
    }
}