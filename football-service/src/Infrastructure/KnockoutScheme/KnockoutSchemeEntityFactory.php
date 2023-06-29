<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutEdgeRoundEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntityFactory;
use Sportal\FootballApi\Domain\Stage\IStageEntity;

class KnockoutSchemeEntityFactory implements \Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntityFactory
{

    private IKnockoutEdgeRoundEntity $startRound;

    private IKnockoutEdgeRoundEntity $endRound;

    private ?bool $smallFinal;

    private IStageEntity $stage;

    private array $rounds;

    /**
     * @inheritDoc
     */
    public function setStartRound(IKnockoutEdgeRoundEntity $startRound): IKnockoutSchemeEntityFactory
    {
        $this->startRound = $startRound;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEndRound(IKnockoutEdgeRoundEntity $endRound): IKnockoutSchemeEntityFactory
    {
        $this->endRound = $endRound;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSmallFinal(?bool $smallFinal): IKnockoutSchemeEntityFactory
    {
        $this->smallFinal = $smallFinal;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setStage(IStageEntity $stage): IKnockoutSchemeEntityFactory
    {
        $this->stage = $stage;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRounds(array $rounds): IKnockoutSchemeEntityFactory
    {
        $this->rounds = $rounds;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setEmpty(): IKnockoutSchemeEntityFactory
    {
        return new KnockoutSchemeEntityFactory();
    }

    /**
     * @inheritDoc
     */
    public function setFrom(IKnockoutSchemeEntity $schemeEntity): IKnockoutSchemeEntityFactory
    {
        $factory = new KnockoutSchemeEntityFactory();
        $factory->setStartRound($schemeEntity->getStartRound());
        $factory->setEndRound($schemeEntity->getEndRound());
        $factory->setSmallFinal($schemeEntity->getSmallFinal());
        $factory->setStage($schemeEntity->getStage());
        $factory->setRounds($schemeEntity->getRounds());

        return $factory;
    }

    /**
     * @inheritDoc
     */
    public function create(): IKnockoutSchemeEntity
    {
        return new KnockoutSchemeEntity($this->startRound, $this->endRound, $this->smallFinal, $this->stage, $this->rounds);
    }
}