<?php


namespace Sportal\FootballApi\Application\Group\Input\Upsert;


use Sportal\FootballApi\Application\IDto;

class CollectionDto implements IDto, \JsonSerializable
{

    private array $groups;

    private string $stageId;

    public function __construct(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return Dto[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stageId;
    }

    /**
     * @param string $stageId
     */
    public function setStageId(string $stageId): void
    {
        $this->stageId = $stageId;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}