<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\IStandingEntity;
use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class StandingEntity extends GeneratedIdDatabaseEntity implements IStandingEntity
{
    private ?string $id;

    private StandingType $type;

    private StandingEntityName $entityName;

    private string $entityId;

    /**
     * StandingEntity constructor.
     * @param string|null $id
     * @param StandingType $type
     * @param StandingEntityName $entityName
     * @param string $entityId
     */
    public function __construct(?string $id, StandingType $type, StandingEntityName $entityName, string $entityId)
    {
        $this->id = $id;
        $this->type = $type;
        $this->entityName = $entityName;
        $this->entityId = $entityId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return StandingType
     */
    public function getType(): StandingType
    {
        return $this->type;
    }

    /**
     * @return StandingEntityName
     */
    public function getEntityName(): StandingEntityName
    {
        return $this->entityName;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }


    /**
     * @inheritDoc
     */
    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $entity = clone $this;
        $entity->id = $id;
        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function getDatabaseEntry(): array
    {
        return [
            'type' => $this->type->getValue(),
            'entity' => $this->entityName->getValue(),
            'entity_id' => $this->entityId
        ];
    }
}