<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\IStandingEntity;
use Sportal\FootballApi\Domain\Standing\IStandingEntityFactory;
use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;

class StandingEntityFactory implements IStandingEntityFactory
{
    private ?string $id = null;

    private StandingType $type;

    private StandingEntityName $entityName;

    private string $entityId;

    public function setEmpty(): IStandingEntityFactory
    {
        return new StandingEntityFactory();
    }

    /**
     * @param string|null $id
     * @return IStandingEntityFactory
     */
    public function setId(?string $id): IStandingEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param StandingType $type
     * @return IStandingEntityFactory
     */
    public function setType(StandingType $type): IStandingEntityFactory
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param StandingEntityName $entityName
     * @return IStandingEntityFactory
     */
    public function setEntityName(StandingEntityName $entityName): IStandingEntityFactory
    {
        $this->entityName = $entityName;
        return $this;
    }

    /**
     * @param string $entityId
     * @return IStandingEntityFactory
     */
    public function setEntityId(string $entityId): IStandingEntityFactory
    {
        $this->entityId = $entityId;
        return $this;
    }

    public function create(): IStandingEntity
    {
        return new StandingEntity($this->id, $this->type, $this->entityName, $this->entityId);
    }
}