<?php


namespace Sportal\FootballApi\Application\Venue\Output\ListAll;


use Sportal\FootballApi\Application\City\Mapper\CityEntityToDtoMapper;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\Venue\Output\ListAll;
use Sportal\FootballApi\Application\Venue\Dto\VenueProfileDto;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;

class Mapper
{
    public function map(IVenueEntity $venueEntity)
    {
        if (is_null($venueEntity)) {
            return null;
        }
        
        return new ListVenueDto(
            $venueEntity->getId(),
            $venueEntity->getName(),
            !is_null($venueEntity->getCountry()) ? CountryDto::fromCountryEntity($venueEntity->getCountry()) : null,
            !is_null($venueEntity->getCity()) ? (new CityEntityToDtoMapper())->map($venueEntity->getCity()) : null,
            !is_null($venueEntity->getProfile()) ? VenueProfileDto::create($venueEntity->getProfile()) : null
        );
    }
}