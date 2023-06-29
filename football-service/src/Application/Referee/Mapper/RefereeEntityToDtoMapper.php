<?php


namespace Sportal\FootballApi\Application\Referee\Mapper;


use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\Referee\Dto\RefereeDto;
use Sportal\FootballApi\Domain\Referee\IRefereeEntity;

class RefereeEntityToDtoMapper
{
    public function map(IRefereeEntity $refereeEntity)
    {
        return new RefereeDto(
            $refereeEntity->getId(),
            $refereeEntity->getName(),
            CountryDto::fromCountryEntity($refereeEntity->getCountry()),
            !is_null($refereeEntity->getBirthdate()) ? $refereeEntity->getBirthdate()->format("Y-m-d") : null,
            $refereeEntity->isActive(),
            $refereeEntity->getGender()
        );
    }
}