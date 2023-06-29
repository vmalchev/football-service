<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Team;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

class Mapper
{

    public function map(ITeamEntity $teamEntity): TeamDto
    {
        return new TeamDto(
            $teamEntity->getId(),
            $teamEntity->getName(),
            $teamEntity->getThreeLetterCode(),
            $teamEntity->getGender(),
            $teamEntity->getType(),
        );
    }
}