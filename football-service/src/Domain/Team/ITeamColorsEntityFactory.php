<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\Match\ColorTeamType;

interface ITeamColorsEntityFactory
{
    public function setEntity(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntityFactory;

    public function setEmpty(): ITeamColorsEntityFactory;

    public function setEntityId(string $entityId): ITeamColorsEntityFactory;

    public function setEntityType(string $entityType): ITeamColorsEntityFactory;

    public function setColors(array $colors): ITeamColorsEntityFactory;

    public function setColor(ColorTeamType $colorTeamType, ?string $colorCode): ITeamColorsEntityFactory;

    public function create(): ITeamColorsEntity;
}