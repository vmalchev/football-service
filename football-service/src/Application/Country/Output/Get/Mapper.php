<?php


namespace Sportal\FootballApi\Application\Country\Output\Get;


use Sportal\FootballApi\Domain\Country\ICountryEntity;

class Mapper
{
    public function map(?ICountryEntity $countryEntity): ?Dto
    {
        if (is_null($countryEntity)) {
            return null;
        }

        return new Dto(
            $countryEntity->getId(),
            $countryEntity->getName(),
            $countryEntity->getCode()
        );
    }
}