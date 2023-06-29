<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\Match\ColorTeamType;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;

class TeamColorsEntity implements ITeamColorsEntity
{
    private string $entityId;
    private string $entityType;
    private array $colors;

    /**
     * TeamColorsEntity constructor.
     * @param string $entityId
     * @param string $entityType
     * @param array $colors
     */
    public function __construct(string $entityId, string $entityType, array $colors)
    {
        $this->entityId = $entityId;
        $this->entityType = $entityType;
        $this->colors = $colors;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TeamColorsTable::FIELD_ENTITY_ID => $this->entityId,
            TeamColorsTable::FIELD_ENTITY_TYPE => $this->entityType,
            TeamColorsTable::FIELD_COLORS => json_encode($this->colors)
        ];
    }

    public function getPrimaryKey(): array
    {
        return [TeamColorsTable::FIELD_ENTITY_TYPE => $this->getEntityType(),
            TeamColorsTable::FIELD_ENTITY_ID => $this->getEntityId()];
    }

    public function getColors(): array
    {
        return $this->colors;
    }

    public function getColorByType(ColorTeamType $colorTeamType): ?string
    {
        foreach ($this->colors as $color) {
            if ($color['type'] == $colorTeamType) {
                return $color['color_code'];
            }
        }
        return null;
    }
}