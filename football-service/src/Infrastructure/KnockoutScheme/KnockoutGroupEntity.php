<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


class KnockoutGroupEntity implements \Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutGroupEntity
{

    private string $id;

    private int $order;

    private array $teams;

    private array $matches;

    private ?string $childObjectId;

    /**
     * KnockoutGroupEntity constructor.
     * @param string $id
     * @param int $order
     * @param array $teams
     * @param array $matches
     * @param string|null $child_object_id
     */
    public function __construct(string $id, int $order, array $teams, array $matches, ?string $child_object_id)
    {
        $this->id = $id;
        $this->order = $order;
        $this->teams = $teams;
        $this->matches = $matches;
        $this->childObjectId = $child_object_id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getTeams(): array
    {
        return $this->teams;
    }

    public function getMatches(): array
    {
        return $this->matches;
    }

    public function getChildObjectId(): ?string
    {
        return $this->childObjectId;
    }
}