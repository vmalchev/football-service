<?php

namespace Sportal\FootballApi\Application\Season\Output\Stage;

use Sportal\FootballApi\Domain\Stage\IStageEntity;

class Mapper
{
    private \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper;

    private \Sportal\FootballApi\Application\Round\Output\Profile\Mapper $roundMapper;

    /**
     * @param \Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper
     * @param \Sportal\FootballApi\Application\Round\Output\Profile\Mapper $roundMapper
     */
    public function __construct(\Sportal\FootballApi\Application\Stage\Output\Get\Mapper $stageMapper,
                                \Sportal\FootballApi\Application\Round\Output\Profile\Mapper $roundMapper)
    {
        $this->stageMapper = $stageMapper;
        $this->roundMapper = $roundMapper;
    }


    public function map(IStageEntity $stageEntity, array $rounds): ?Dto
    {
        return new Dto(
            $this->stageMapper->map($stageEntity),
            array_map([$this->roundMapper, 'map'], $rounds)
        );
    }
}