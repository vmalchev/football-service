<?php

namespace Sportal\FootballApi\Domain\Standing;

interface IStandingEntityFactory
{
    public function setEmpty(): IStandingEntityFactory;

    /**
     * @param string|null $id
     * @return IStandingEntityFactory
     */
    public function setId(?string $id): IStandingEntityFactory;

    /**
     * @param StandingType $type
     * @return IStandingEntityFactory
     */
    public function setType(StandingType $type): IStandingEntityFactory;

    /**
     * @param StandingEntityName $entityName
     * @return IStandingEntityFactory
     */
    public function setEntityName(StandingEntityName $entityName): IStandingEntityFactory;

    /**
     * @param string $entityId
     * @return IStandingEntityFactory
     */
    public function setEntityId(string $entityId): IStandingEntityFactory;
    
    public function create(): IStandingEntity;
}