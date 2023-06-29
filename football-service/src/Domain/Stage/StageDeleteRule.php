<?php

namespace Sportal\FootballApi\Domain\Stage;

use Sportal\FootballApi\Domain\Match\IMatchFilterBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Stage\Exception\InvalidStageException;

class StageDeleteRule implements IStageDeleteRule
{

    private IMatchFilterBuilder $matchFilterBuilder;

    private IMatchRepository $matchRepository;

    public function __construct(IMatchFilterBuilder $matchFilterBuilder,
                                IMatchRepository $matchRepository)
    {
        $this->matchFilterBuilder = $matchFilterBuilder;
        $this->matchRepository = $matchRepository;
    }


    /**
     * @param IStageEntity $stageEntity
     * @throws InvalidStageException
     */
    public function validate(IStageEntity $stageEntity)
    {
        $filter = $this->matchFilterBuilder
            ->setStageIds([$stageEntity->getId()])
            ->create();

        $match = $this->matchRepository->findByFilter($filter);
        if (!empty($match)) {
            $matchIds = array_map(fn($match) => $match->getId(), $match);
            throw new InvalidStageException(
                'Only stages without link to a match can be deleted. Stage is linked with match ' . implode(',', $matchIds)
            );
        }
    }

}