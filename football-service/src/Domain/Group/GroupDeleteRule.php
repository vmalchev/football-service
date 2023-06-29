<?php

namespace Sportal\FootballApi\Domain\Group;

use Sportal\FootballApi\Domain\Group\Exception\InvalidGroupException;
use Sportal\FootballApi\Domain\Match\IMatchFilterBuilder;
use Sportal\FootballApi\Domain\Match\IMatchRepository;

class GroupDeleteRule implements IGroupDeleteRule
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
     * @throws InvalidGroupException
     */
    public function validate(IGroupEntity $groupEntity)
    {
        $filter = $this->matchFilterBuilder
            ->setGroupIds([$groupEntity->getId()])
            ->create();

        $matches = $this->matchRepository->findByFilter($filter);
        if (!empty($matches)) {
            $matchIds = array_map(fn($match) => $match->getId(), $matches);
            throw new InvalidGroupException(
                'Only groups without link to a match can be deleted. Group is linked with match ' . implode(',', $matchIds)
            );
        }
    }

}