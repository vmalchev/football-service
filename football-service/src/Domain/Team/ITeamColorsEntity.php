<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\Match\ColorTeamType;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

interface ITeamColorsEntity extends IDatabaseEntity
{
    /**
     * @return string
     */
    public function getEntityId(): string;

    /**
     * @return string
     */
    public function getEntityType(): string;

    /**
     * @param ColorTeamType $colorTeamType
     * @return string|null
     */
    public function getColorByType(ColorTeamType $colorTeamType): ?string;

    /**
     * @return array
     */
    public function getColors(): array;
}