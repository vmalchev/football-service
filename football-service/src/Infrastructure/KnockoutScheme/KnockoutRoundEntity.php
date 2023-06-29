<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


class KnockoutRoundEntity implements \Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntity
{

    private string $name;

    private array $groups;

    /**
     * KnockoutRoundEntity constructor.
     * @param string $name
     * @param array $groups
     */
    public function __construct(string $name, array $groups)
    {
        $this->name = $name;
        $this->groups = array_values($groups);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }
}