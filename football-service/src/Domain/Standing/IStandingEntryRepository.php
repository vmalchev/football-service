<?php

namespace Sportal\FootballApi\Domain\Standing;

interface IStandingEntryRepository
{
    /**
     * @param IStandingEntity $standingEntity
     * @param IStandingEntry[] $entries
     * @return IStandingEntry[] entries as stored in persistence
     */
    public function upsert(IStandingEntity $standingEntity, array $entries): array;
}