<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Group;

use Sportal\FootballApi\Application\KnockoutScheme\Output\Match\MatchDto;
use Sportal\FootballApi\Application\KnockoutScheme\Output\Team\TeamDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_KnockoutGroup")
 */
class GroupDto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="id")
     * @var string
     */
    private string $id;

    /**
     * @SWG\Property(property="order")
     * @var int
     */
    private int $order;

    /**
     * @SWG\Property(property="teams")
     * @var TeamDto[]
     */
    private array $teams;

    /**
     * @SWG\Property(property="matches")
     * @var MatchDto[]
     */
    private array $matches;

    /**
     * @SWG\Property(property="child_object_id")
     * @var string|null
     */
    private ?string $child_object_id;

    /**
     * GroupDto constructor.
     * @param string $id
     * @param int $order
     * @param TeamDto[] $teams
     * @param MatchDto[] $matches
     * @param string|null $child_object_id
     */
    public function __construct(string $id, int $order, array $teams, array $matches, ?string $child_object_id)
    {
        $this->id = $id;
        $this->order = $order;
        $this->teams = $teams;
        $this->matches = $matches;
        $this->child_object_id = $child_object_id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return TeamDto[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return MatchDto[]
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

    /**
     * @return string|null
     */
    public function getChildObjectId(): ?string
    {
        return $this->child_object_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}