<?php


namespace Sportal\FootballApi\Domain\Standing;

interface IStandingRepository
{
    public function upsert(IStandingEntity $standingEntity): IStandingEntity;

    public function findExisting(StandingType $type, StandingEntityName $entity, string $entityId): ?IStandingEntity;
}