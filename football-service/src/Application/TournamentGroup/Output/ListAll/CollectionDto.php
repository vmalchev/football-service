<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Output\ListAll;

use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_TournamentGroupCollection")
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