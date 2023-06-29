<?php

namespace Sportal\FootballApi\Infrastructure\LineupPlayerType;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class LineupPlayerTypeEntity extends GeneratedIdDatabaseEntity implements ILineupPlayerTypeEntity
{
    private ?string $id;
    private string $name;
    private string $category;
    private string $code;
    private int $sortOrder;

    /**
     * EventPlayerTypeEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string $category
     * @param string $code
     * @param int $sortOrder
     */
    public function __construct(?string $id, string $name, string $category, string $code, int $sortOrder)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->code = $code;
        $this->sortOrder = $sortOrder;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $updated = clone $this;
        $updated->id = $id;
        return $updated;
    }

    public function getDatabaseEntry(): array
    {
        return [
            LineupPlayerTypeTableMapper::FIELD_ID,
            LineupPlayerTypeTableMapper::FIELD_NAME,
            LineupPlayerTypeTableMapper::FIELD_CATEGORY,
            LineupPlayerTypeTableMapper::FIELD_CODE,
        ];
    }

    /**
     * @return int
     */
    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }
}