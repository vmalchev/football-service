<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;

use Sportal\FootballApi\Application\Exception\DuplicateKeyException;

class TournamentGroupRule implements ITournamentGroupRule
{

    private ITournamentGroupRepository $tournamentGroupRepository;

    public function __construct(ITournamentGroupRepository $tournamentGroupRepository)
    {
        $this->tournamentGroupRepository = $tournamentGroupRepository;
    }

    /**
     * @inheritDoc
     * @throws DuplicateKeyException
     */
    public function validate(ITournamentGroupEntity $tournamentGroup, bool $insert)
    {
        $groups = $this->tournamentGroupRepository->findAll();

        if ($insert) {
            $groups[] = $tournamentGroup;
        } else {
            $position = array_search($tournamentGroup->getCode(), array_map(fn($group) => $group->getCode(), $groups));
            $groups[$position] = $tournamentGroup;
        }

        $nameArray = array_map(fn($group) => $group->getName(), $groups);
        $codeArray = array_map(fn($group) => $group->getCode(), $groups);

        if (array_unique($nameArray) !== $nameArray) {
            throw new DuplicateKeyException('Name ' . $tournamentGroup->getName());
        } elseif (array_unique($codeArray) !== $codeArray) {
            throw new DuplicateKeyException('Code ' . $tournamentGroup->getCode());
        }
    }

}