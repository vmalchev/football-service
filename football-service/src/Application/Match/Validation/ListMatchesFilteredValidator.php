<?php


namespace Sportal\FootballApi\Application\Match\Validation;



use Sportal\FootballApi\Application\Match\Exception\InvalidMatchInputException;
use Sportal\FootballApi\Application\Match\Input\ListAll\Dto;

class ListMatchesFilteredValidator
{

    /**
     * @param Dto $dto
     * @throws InvalidMatchInputException
     */
    public function validate(Dto $dto): void
    {
        if (!empty($dto->getRoundIds()) && empty($dto->getStageIds())) {
            throw new InvalidMatchInputException("The stage_ids filter must always be provided when using round_ids");
        }

        if (!empty($dto->getRoundIds())) {
            if (count($dto->getRoundIds()) > 1 && count($dto->getStageIds()) > 1) {
                throw new InvalidMatchInputException("The stage_ids and round_ids filters cannot contain multiple values when provided together");
            }
        }

        if (!empty($dto->getRoundFilters())) {
            if (!empty($dto->getRoundIds()) || !empty($dto->getStageIds())) {
                throw new InvalidMatchInputException("The round_ids and stage_ids filters cannot be used when round_filter parameter is provided");
            }
        }

        if (!($dto->getTournamentIds() || $dto->getSeasonIds() || $dto->getStageIds() || $dto->getGroupIds() ||
            $dto->getRoundIds() || $dto->getFromKickoffTime() || $dto->getToKickoffTime() || $dto->getTeamIds() ||
            $dto->getStatusTypes() || $dto->getStatusCodes() || $dto->getRefereeId() || $dto->getVenueId() || $dto->getRoundFilters())) {

            throw new InvalidMatchInputException("No valid filters provided");
        }
    }

}