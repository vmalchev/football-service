<?php


namespace Sportal\FootballApi\Application\Team\Output\Colors;


use Sportal\FootballApi\Application\Team\Dto\TeamColorsDto;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;

class Mapper
{
    public function map(ITeamColorsEntity $teamColorsEntity): TeamColorsDto
    {
        return new TeamColorsDto(
            $teamColorsEntity->getEntityType(),
            $teamColorsEntity->getEntityId(),
            $teamColorsEntity->getColors());
    }
}