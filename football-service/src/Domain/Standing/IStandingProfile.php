<?php


namespace Sportal\FootballApi\Domain\Standing;


interface IStandingProfile
{
    public function getStandingEntity(): IStandingEntity;

    /**
     * @return IStandingEntry[]
     */
    public function getEntries(): array;

    public function setStandingEntity(IStandingEntity $standingEntity): IStandingProfile;

    /**
     * @param IStandingEntry[] $entries
     * @return IStandingProfile
     */
    public function setEntries(array $entries): IStandingProfile;

    /**
     * @param IStandingEntryRule[] $entryRules
     * @return IStandingProfile
     */
    public function setEntryRules(array $entryRules): IStandingProfile;


    /**
     * @return IStandingEntryRule[]
     */
    public function getEntryRules(): array;


}