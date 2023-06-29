<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;

use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\Match\Exception\InvalidMatchInputException;
use Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert\CollectionDto;
use Sportal\FootballApi\Domain\Match\Exception\InvalidMatchException;
use Sportal\FootballApi\Domain\Match\IMatchRepository;
use Sportal\FootballApi\Domain\Match\MatchFilterBuilder;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupRepository;

class TournamentGroupSelectionRule implements ITournamentGroupSelectionRule
{

    private ITournamentGroupRepository $tournamentGroupRepository;

    private IMatchRepository $matchRepository;

    private MatchFilterBuilder $matchFilterBuilder;

    public function __construct(ITournamentGroupRepository $tournamentGroupRepository,
                                IMatchRepository $matchRepository,
                                MatchFilterBuilder $matchFilterBuilder)
    {
        $this->tournamentGroupRepository = $tournamentGroupRepository;
        $this->matchRepository = $matchRepository;
        $this->matchFilterBuilder = $matchFilterBuilder;
    }

    /**
     * @inheritDoc
     */
    public function validate(CollectionDto $inputDto)
    {
        if (!\DateTime::createFromFormat('Y-m-d', $inputDto->getDate())) {
            throw new \InvalidArgumentException('Specified date is not valid ISO-8601 format');
        }

        $tournamentGroup = $this->tournamentGroupRepository->findByCode($inputDto->getCode());
        if (is_null($tournamentGroup)) {
            throw new NoSuchEntityException('Tournament group does not exist: ' . $inputDto->getCode());
        }

        $matchIds = array_map(fn($dto) => $dto->getMatchId(), $inputDto->getMatches());
        if (!empty($matchIds)) {
            $filter = $this->matchFilterBuilder
                ->setTournamentGroup($inputDto->getCode())
                ->setMatchIds($matchIds)
                ->create();

            $matches = $this->matchRepository->findByFilter($filter);

            if (sizeof($matches) !== sizeof($matchIds)) {
                $missing = array_diff($matchIds, array_map(fn($match) => $match->getId(), $matches));
                foreach ($missing as $item) {
                    if (!$this->matchRepository->existsById($item)) {
                        throw new InvalidMatchInputException('Match with id ' . $item . ' does not exist');
                    } else {
                        throw new InvalidMatchException('Match with id ' . $item . ' not part of tournament group ' . $inputDto->getCode());
                    }
                }
            }
        }
    }

}