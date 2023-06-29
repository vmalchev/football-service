<?php


namespace Sportal\FootballApi\Application\StandingEntryRule\Input\Put;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;

class Dto implements IDto
{
    private StandingType $type;

    private StandingEntityName $entity;

    private string $entity_id;

    /**
     * @var EntryRuleDto[]
     */
    private array $entryRules;

    /**
     * Dto constructor.
     * @param StandingType $type
     * @param StandingEntityName $entity
     * @param string $entity_id
     * @param EntryRuleDto[] $entryRules
     */
    public function __construct(StandingType $type, StandingEntityName $entity, string $entity_id, array $entryRules)
    {
        $this->type = $type;
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->entryRules = $entryRules;
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
    public function getEntity(): StandingEntityName
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entity_id;
    }

    /**
     * @return EntryRuleDto[]
     */
    public function getEntryRules(): array
    {
        return $this->entryRules;
    }


}