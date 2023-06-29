<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


interface IKnockoutRoundEntity
{

    public function getName(): string;

    /**
     * @return IKnockoutGroupEntity[]
     */
    public function getGroups(): array;
}