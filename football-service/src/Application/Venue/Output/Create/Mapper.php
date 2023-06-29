<?php


namespace Sportal\FootballApi\Application\Venue\Output\Create;

use Sportal\FootballApi\Application\City;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\Venue\Dto\VenueProfileDto;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;


class Mapper
{
    /**
     * @var Country\Output\Create\Mapper
     */
    private Country\Output\Get\Mapper $countryMapper;

    /**
     * @var City\Output\Create\Mapper
     */
    private City\Output\Get\Mapper $cityMapper;

    public function __construct(Country\Output\Get\Mapper $countryMapper, City\Output\Get\Mapper $cityMapper)
    {
        $this->countryMapper = $countryMapper;
        $this->cityMapper = $cityMapper;
    }

    public function map(?IVenueEntity $venueEntity): ?Dto
    {
        if (is_null($venueEntity)) {
            return null;
        }

        return new Dto(
            $venueEntity->getId(),
            $venueEntity->getName(),
            $this->countryMapper->map($venueEntity->getCountry()),
            $this->cityMapper->map($venueEntity->getCity()),
            VenueProfileDto::create($venueEntity->getProfile())
        );
    }
}