<?php


namespace Sportal\FootballApi\Application\TeamColors\Output\Get;


use Sportal\FootballApi\Domain\Match\ColorTeamType;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;

class Mapper
{

    public function map(?ITeamColorsEntity $colorsEntity, ColorTeamType $teamType): ?string
    {
        if (is_null($colorsEntity)) {
            return null;
        }

        return $colorsEntity->getColorByType($teamType);
    }
}