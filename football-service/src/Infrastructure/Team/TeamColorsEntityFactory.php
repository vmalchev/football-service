<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\Match\ColorTeamType;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntityFactory;

class TeamColorsEntityFactory implements ITeamColorsEntityFactory
{

    private string $entityId;
    private string $entityType;
    private array $colors = [];

    public function setEntity(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntityFactory
    {
        $factory = new TeamColorsEntityFactory();

        $factory->entityId = $teamColorsEntity->getEntityId();
        $factory->entityType = $teamColorsEntity->getEntityType();
        $factory->colors = $teamColorsEntity->getColors();

        return $factory;
    }

    /**
     * @return ITeamColorsEntityFactory
     */
    public function setEmpty(): ITeamColorsEntityFactory
    {
        return new TeamColorsEntityFactory();
    }

    /**
     * @param string $entityId
     * @return ITeamColorsEntityFactory
     */
    public function setEntityId(string $entityId): ITeamColorsEntityFactory
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * @param string $entityType
     * @return ITeamColorsEntityFactory
     */
    public function setEntityType(string $entityType): ITeamColorsEntityFactory
    {
        $this->entityType = $entityType;
        return $this;
    }

    public function setColors(array $colors): ITeamColorsEntityFactory
    {
        $this->colors = $colors;
        return $this;
    }

    public function create(): ITeamColorsEntity
    {
        return new TeamColorsEntity($this->entityId, $this->entityType, $this->colors);
    }

    public function setColor(ColorTeamType $colorTeamType, ?string $colorCode): ITeamColorsEntityFactory
    {
        if ($colorCode === null) {
            return $this;
        }

        foreach ($this->colors as $key => $color) {
            if ($color['type'] == $colorTeamType) {
                $this->colors[$key]['color_code'] = $colorCode;
                return $this;
            }
        }

        $this->colors[] = [
            'type' => $colorTeamType->getValue(),
            'color_code' => $colorCode
        ];

        return $this;
    }
}