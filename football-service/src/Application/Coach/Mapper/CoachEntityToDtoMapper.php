<?php


namespace Sportal\FootballApi\Application\Coach\Mapper;


use Sportal\FootballApi\Application\Coach\Dto\CoachDto;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;

/**
 * @deprecated use \Sportal\FootballApi\Application\Coach\Output\Get\Mapper
 */
class CoachEntityToDtoMapper
{
    public function map(ICoachEntity $coachEntity)
    {
        return new CoachDto(
            $coachEntity->getId(),
            $coachEntity->getName(),
            CountryDto::fromCountryEntity($coachEntity->getCountry()),
            !is_null($coachEntity->getBirthdate()) ? $coachEntity->getBirthdate()->format("Y-m-d") : null,
            $coachEntity->getGender()
        );
    }
}