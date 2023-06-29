<?php

namespace Sportal\FootballApi\Domain\Match;

use Sportal\FootballApi\Application\Match\Input\ListLivescore\Dto;
use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionRepository;

class LivescoreMatchSelectionResolver
{

    private ITournamentGroupSelectionRepository $tournamentGroupSelectionRepository;

    public function __construct(ITournamentGroupSelectionRepository $tournamentGroupSelectionRepository)
    {
        $this->tournamentGroupSelectionRepository = $tournamentGroupSelectionRepository;
    }

    /**
     * @param Dto $inputDto
     * @return array|null
     */
    public function resolve(Dto $inputDto): ?array
    {
        $tournamentGroup = $inputDto->getTournamentGroup();
        $date = $inputDto->getDate();
        $selectionFilter = is_null($inputDto->getSelectionFilter()) ? LivescoreSelectionFilter::ENABLED()
            : $inputDto->getSelectionFilter();
        $matchIds = $inputDto->getMatchIds();

        if (is_null($date) || is_null($tournamentGroup) || $selectionFilter->equals(LivescoreSelectionFilter::DISABLED())) {
            return $matchIds;
        }

        $tournamentGroupSelectionEntities = $this->tournamentGroupSelectionRepository->findByCodeAndDate(
            $tournamentGroup,
            $date
        );
        if (!empty($tournamentGroupSelectionEntities)) {
            return array_map(fn($entity) => $entity->getMatchId(), $tournamentGroupSelectionEntities);
        }

        return $matchIds;
    }

}