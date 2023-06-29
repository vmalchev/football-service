<?php


namespace Sportal\FootballApi\Infrastructure\KnockoutScheme;


use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutEdgeRoundEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutRoundEntity;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;

class KnockoutSchemeEntity implements IKnockoutSchemeEntity
{

    private IKnockoutEdgeRoundEntity $startRound;

    private IKnockoutEdgeRoundEntity $endRound;

    private ?bool $smallFinal;

    private IStageEntity $stage;

    private array $rounds;

    /**
     * KnockoutSchemeEntity constructor.
     * @param IKnockoutEdgeRoundEntity $startRound
     * @param IKnockoutEdgeRoundEntity $endRound
     * @param bool|null $smallFinal
     * @param IStageEntity $stage
     * @param IKnockoutRoundEntity[] $rounds
     */
    public function __construct(IKnockoutEdgeRoundEntity $startRound,
                                IKnockoutEdgeRoundEntity $endRound,
                                ?bool $smallFinal,
                                IStageEntity $stage,
                                array $rounds)
    {
        $this->startRound = $startRound;
        $this->endRound = $endRound;
        $this->smallFinal = $smallFinal;
        $this->stage = $stage;
        $this->rounds = $rounds;
    }

    public function getStartRound(): IKnockoutEdgeRoundEntity
    {
        return $this->startRound;
    }

    public function getEndRound(): IKnockoutEdgeRoundEntity
    {
        return $this->endRound;
    }

    public function getSmallFinal(): ?bool
    {
        return is_null($this->smallFinal) ? null : $this->smallFinal;
    }

    public function getStage(): IStageEntity
    {
        return $this->stage;
    }

    public function getRounds(): array
    {
        return $this->rounds;
    }
}