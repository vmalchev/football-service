<?php


namespace Sportal\FootballApi\Domain\KnockoutScheme;


use Sportal\FootballApi\Domain\Stage\IStageEntity;

interface IKnockoutSchemeEntityFactory
{

    /**
     * @param IKnockoutEdgeRoundEntity $startRound
     * @return IKnockoutSchemeEntityFactory
     */
    public function setStartRound(IKnockoutEdgeRoundEntity $startRound): IKnockoutSchemeEntityFactory;

    /**
     * @param IKnockoutEdgeRoundEntity $endRound
     * @return IKnockoutSchemeEntityFactory
     */
    public function setEndRound(IKnockoutEdgeRoundEntity $endRound): IKnockoutSchemeEntityFactory;

    /**
     * @param bool|null $smallFinal
     * @return IKnockoutSchemeEntityFactory
     */
    public function setSmallFinal(?bool $smallFinal): IKnockoutSchemeEntityFactory;

    /**
     * @param IStageEntity $stage
     * @return IKnockoutSchemeEntityFactory
     */
    public function setStage(IStageEntity $stage): IKnockoutSchemeEntityFactory;

    /**
     * @param IKnockoutRoundEntity[] $rounds
     * @return IKnockoutSchemeEntityFactory
     */
    public function setRounds(array $rounds): IKnockoutSchemeEntityFactory;

    /**
     * @return IKnockoutSchemeEntityFactory
     */
    public function setEmpty(): IKnockoutSchemeEntityFactory;

    /**
     * @param IKnockoutSchemeEntity $schemeEntity
     * @return IKnockoutSchemeEntityFactory
     */
    public function setFrom(IKnockoutSchemeEntity $schemeEntity): IKnockoutSchemeEntityFactory;

    /**
     * @return IKnockoutSchemeEntity
     */
    public function create(): IKnockoutSchemeEntity;
}