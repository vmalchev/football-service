<?php


namespace Sportal\FootballApi\Domain\Standing;


class StandingProfile implements IStandingProfile
{
    private IStandingEntity $standing;

    private array $entries;

    private array $entryRules;

    public function getStandingEntity(): IStandingEntity
    {
        return $this->standing;
    }

    /**
     * @inheritDoc
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    public function setStandingEntity(IStandingEntity $standingEntity): IStandingProfile
    {
        $profile = clone $this;
        $profile->standing = $standingEntity;
        return $profile;
    }

    /**
     * @inheritDoc
     */
    public function setEntries(array $entries): IStandingProfile
    {
        $profile = clone $this;
        $profile->entries = $entries;
        return $profile;
    }

    /**
     * @inheritDoc
     */
    public function setEntryRules(array $entryRules): IStandingProfile
    {
        $profile = clone $this;
        $profile->entryRules = $entryRules;
        return $profile;
    }

    /**
     * @inheritDoc
     */
    public function getEntryRules(): array
    {
        return $this->entryRules;
    }
}