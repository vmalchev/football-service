<?php

namespace Sportal\FootballApi\Application\Season\Output\Details;

use Sportal\FootballApi\Application\Tournament;
use Sportal\FootballApi\Domain\Season\ISeasonDetails;

class Mapper
{

    /**
     * @var \Sportal\FootballApi\Application\Season\Output\Get\Mapper
     */
    private \Sportal\FootballApi\Application\Season\Output\Get\Mapper $seasonMapper;

    /**
     * @var \Sportal\FootballApi\Application\Season\Output\Stage\Mapper
     */
    private \Sportal\FootballApi\Application\Season\Output\Stage\Mapper $stageMapper;

    public function __construct(\Sportal\FootballApi\Application\Season\Output\Get\Mapper $seasonMapper,
                                \Sportal\FootballApi\Application\Season\Output\Stage\Mapper $stageMapper)
    {
        $this->seasonMapper = $seasonMapper;
        $this->stageMapper = $stageMapper;
    }

    public function map(?ISeasonDetails $seasonDetails):  ?Dto
    {
        $stageDtos = [];
        foreach ($seasonDetails->getStages() as $stage) {
            $stageDtos[] = $this->stageMapper->map($stage, $seasonDetails->getRounds($stage->getId()));
        }

        return new Dto(
            $this->seasonMapper->map($seasonDetails->getSeason()),
            $stageDtos
        );
    }
}