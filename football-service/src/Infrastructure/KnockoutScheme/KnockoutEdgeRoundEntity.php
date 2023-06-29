<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


class KnockoutEdgeRoundEntity implements \Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutEdgeRoundEntity
{

    private string $name;

    /**
     * KnockoutLimitRoundEntity constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}