<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\EdgeRound;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutEdgeRoundEntity;

class Mapper
{

    public function map(?IKnockoutEdgeRoundEntity $edgeRoundEntity): EdgeRoundDto
    {
        return new EdgeRoundDto($edgeRoundEntity->getName());
    }
}