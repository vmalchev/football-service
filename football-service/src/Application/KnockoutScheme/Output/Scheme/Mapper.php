<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Scheme;


use Sportal\FootballApi\Application\KnockoutScheme\Output;
use Sportal\FootballApi\Domain\KnockoutScheme\IKnockoutSchemeEntity;

class Mapper
{

    private Output\EdgeRound\Mapper $edgeRoundMapper;

    private Output\Round\Mapper $roundMapper;

    private \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper;

    /**
     * Mapper constructor.
     * @param Output\EdgeRound\Mapper $edgeRoundMapper
     * @param Output\Round\Mapper $roundMapper
     * @param \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper
     */
    public function __construct(Output\EdgeRound\Mapper $edgeRoundMapper, Output\Round\Mapper $roundMapper, \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper)
    {
        $this->edgeRoundMapper = $edgeRoundMapper;
        $this->roundMapper = $roundMapper;
        $this->stageMapper = $stageMapper;
    }


    public function map(IKnockoutSchemeEntity $schemeEntity): SchemeDto
    {
        $roundDtos = [];

        foreach ($schemeEntity->getRounds() as $round) {
            $roundDtos[] = $this->roundMapper->map($round);
        }

        return new SchemeDto(
            $this->edgeRoundMapper->map($schemeEntity->getStartRound()),
            $this->edgeRoundMapper->map($schemeEntity->getEndRound()),
            $schemeEntity->getSmallFinal(),
            $this->stageMapper->map($schemeEntity->getStage()),
            $roundDtos
        );
    }
}