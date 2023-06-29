<?php


namespace Sportal\FootballApi\Infrastructure\President;


use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class PresidentEntity extends GeneratedIdDatabaseEntity implements IPresidentEntity
{
    private ?string $id;
    private string $name;

    /**
     * PresidentEntity constructor.
     * @param string|null $id
     * @param string $name
     */
    public function __construct(?string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
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
            PresidentTable::FIELD_NAME => $this->getName(),
            PresidentTable::FIELD_UPDATED_AT => (new \DateTime())->format(\DateTime::ATOM)
        ];
    }
}