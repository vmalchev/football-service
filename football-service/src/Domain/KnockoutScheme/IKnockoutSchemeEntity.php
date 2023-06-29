<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface IKnockoutSchemeEntity
{

    public function getStartRound(): IKnockoutEdgeRoundEntity;

    public function getEndRound(): IKnockoutEdgeRoundEntity;

    public function getSmallFinal(): ?bool;

    public function getStage(): IStageEntity;

    /**
     * @return IKnockoutRoundEntity[]
     */
    public function getRounds(): array;
}