<?php


namespace Sportal\FootballApi\Application\City\Output\Get;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Application\Country;


class Mapper
{
    /**
     * @var Country\Output\Get\Mapper
     */
    private Country\Output\Get\Mapper $countryMapper;

    public function __construct(Country\Output\Get\Mapper $countryMapper)
    {
        $this->countryMapper = $countryMapper;
    }

    public function map(?ICityEntity $cityEntity): ?Dto
    {
        if (is_null($cityEntity)) {
            return null;
        }

        return new Dto(
            $cityEntity->getId(),
            $cityEntity->getName(),
            $this->countryMapper->map($cityEntity->getCountry())
        );
    }
}