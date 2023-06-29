<?php


namespace Sportal\FootballApi\Application\Standing\Input\Put;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Standing\StandingType;

class Dto implements IDto
{
    private StandingType $type;

    private string $entity;

    private string $entity_id;

    private array $entries;

    /**
     * Dto constructor.
     * @param StandingType $type
     * @param string $entity
     * @param string $entity_id
     * @param array $entries
     */
    public function __construct(StandingType $type, string $entity, string $entity_id, array $entries)
    {
        $this->type = $type;
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->entries = $entries;
    }


    /**
     * @return StandingType
     */
    public function getType(): StandingType
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getEntity(): string
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
     * @return array
     */
    public function getEntries(): array
    {
        return $this->entries;
    }


}