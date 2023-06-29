<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\IStandingEntryRule;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class StandingEntryRule extends GeneratedIdDatabaseEntity implements IStandingEntryRule
{
    private ?string $id;

    private string $standingId;

    private string $standingRuleId;

    private int $rank;

    /**
     * StandingEntryRule constructor.
     * @param string|null $id
     * @param string $standingId
     * @param string $standingRuleId
     * @param int $rank
     */
    public function __construct(string $standingId, string $standingRuleId, int $rank, ?string $id = null)
    {
        $this->id = $id;
        $this->standingId = $standingId;
        $this->standingRuleId = $standingRuleId;
        $this->rank = $rank;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStandingId(): string
    {
        return $this->standingId;
    }

    /**
     * @return string
     */
    public function getStandingRuleId(): string
    {
        return $this->standingRuleId;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
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
            StandingEntryRuleTableMapper::FIELD_STANDING_ID => $this->standingId,
            StandingEntryRuleTableMapper::FIELD_STANDING_RULE_ID => $this->standingRuleId,
            StandingEntryRuleTableMapper::FIELD_RANK => $this->rank
        ];
    }
}