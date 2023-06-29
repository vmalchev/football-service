<?php


namespace Sportal\FootballApi\Application\City\Mapper;


use Sportal\FootballApi\Application\City\Dto\CityDto;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Domain\City\ICityEntity;

class CityEntityToDtoMapper
{
    public function map(ICityEntity $cityEntity)
    {
        return new CityDto(
            $cityEntity->getId(),
            $cityEntity->getName(),
            !is_null($cityEntity->getCountry()) ? CountryDto::fromCountryEntity($cityEntity->getCountry()) : null
        );
    }
}