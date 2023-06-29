<?php


namespace Sportal\FootballApi\Application\Group\Output\Upsert;


use Sportal\FootballApi\Application\Group\Output\Get\Dto;
use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_StageGroupCollection")
 */
class CollectionDto implements IDto, \JsonSerializable
{

    /**
     * @var Dto[]
     * @SWG\Property(property="groups")
     */
    private array $groups;

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

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}