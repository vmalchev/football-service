<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Team;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

class Mapper
{

    public function map(?ITeamEntity $team, ?string $teamColor): ?Dto
    {
        if (is_null($team)) {
            return null;
        }

        return new Dto(
            $team->getId(),
            $team->getName(),
            $team->getThreeLetterCode(),
            $team->getGender(),
            $team->getShortName(),
            $team->getType(),
            $teamColor
        );
    }
}