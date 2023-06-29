<?php

namespace Sportal\FootballApi\Domain\Match;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRepository;

class LivescoreMatchFilterValidator
{

    private ITournamentGroupRepository $tournamentGroupRepository;

    /**
     * @param ITournamentGroupRepository $tournamentGroupRepository
     */
    public function __construct(ITournamentGroupRepository $tournamentGroupRepository)
    {
        $this->tournamentGroupRepository = $tournamentGroupRepository;
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    public function validate(MatchFilter $filter): void
    {
        $this->validateTournamentGroups($filter);
    }

    /**
     * @param MatchFilter $filter
     * @throws NoSuchEntityException
     */
    private function validateTournamentGroups(MatchFilter $filter): void
    {
        if (is_null($filter->getTournamentGroup())) {
            return;
        }

        if (!$this->tournamentGroupRepository->existsByCode($filter->getTournamentGroup())) {
            throw new NoSuchEntityException("Tournament group with code " . $filter->getTournamentGroup() . " does not exist.");
        }
    }

}