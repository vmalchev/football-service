<?php

namespace Sportal\FootballApi\Application\Match\Validation;

use Sportal\FootballApi\Application\Match\Exception\InvalidMatchInputException;
use Sportal\FootballApi\Application\Match\Input\ListLivescore\Dto;
use Sportal\FootballApi\Domain\Match\LivescoreSelectionFilter;

class LivescoreMatchesValidator
{

    /**
     * @param Dto $dto
     * @throws InvalidMatchInputException
     */
    public function validate(Dto $dto): void
    {
        $date = $dto->getDate();
        $utcOffset = $dto->getUtcOffset();
        $matchIds = $dto->getMatchIds();
        $tournamentGroup = $dto->getTournamentGroup();
        $statusTypes = $dto->getStatusTypes();

        if (!is_null($date) || !is_null($utcOffset)) {
            if (is_null($date) || is_null($utcOffset)) {
                throw new InvalidMatchInputException("Filtering by date requires both date and utc_offset parameters");
            }

            if (!is_null($matchIds)) {
                throw new InvalidMatchInputException("Filtering both by date and match IDs is not allowed.");
            }

            $utcOffset = $utcOffset >= 0 ? $utcOffset : -$utcOffset;
            if (fmod($utcOffset, 1) !== 0.0 && fmod($utcOffset - 0.5, 1)  !== 0.0 && fmod($utcOffset - 0.75, 1)  !== 0.0) {
                throw new InvalidMatchInputException("utc_offset must be a number in one of the following fractions: .0, .5, .75");
            }
        }

        if (!is_null($matchIds)) {
            if (!is_null($tournamentGroup)) {
                throw new InvalidMatchInputException("tournament_group parameter is allowed only when filtering by date");
            }
            if (!is_null($statusTypes)) {
                throw new InvalidMatchInputException("status_types and match_ids parameters cannot be used together");
            }
        }

        if (is_null($date) && is_null($matchIds)) {
            throw new InvalidMatchInputException("No valid filters are provided.");
        }

        if (!is_null($dto->getSelectionFilter()) && $dto->getSelectionFilter()->equals(LivescoreSelectionFilter::ENABLED())) {
            if (is_null($date) || is_null($tournamentGroup)) {
                throw new InvalidMatchInputException('selection_filter requires both date and tournament_group parameters');
            }
        }
    }
}