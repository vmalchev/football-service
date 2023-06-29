<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Round;

use Sportal\FootballApi\Application\KnockoutScheme\Output\Group\GroupDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_KnockoutRound")
 */
class RoundDto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * @SWG\Property(property="groups")
     * @var GroupDto[]
     */
    private array $groups;

    /**
     * RoundDto constructor.
     * @param string $name
     * @param GroupDto[] $groups
     */
    public function __construct(string $name, array $groups)
    {
        $this->name = $name;
        $this->groups = $groups;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return GroupDto[]
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