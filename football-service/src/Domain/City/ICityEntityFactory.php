<?php


namespace Sportal\FootballApi\Domain\City;


use Sportal\FootballApi\Domain\Country\ICountryEntity;

interface ICityEntityFactory
{
    public function setEntity(ICityEntity $cityEntity): ICityEntityFactory;

    public function setEmpty(): ICityEntityFactory;

    public function setId(string $id): ICityEntityFactory;

    public function setName(string $name): ICityEntityFactory;

    public function setCountryId(string $countryId): ICityEntityFactory;

    public function setCountry(?ICountryEntity $countryEntity): ICityEntityFactory;

    public function create(): ICityEntity;

    public function createFromArray(array $data): ICityEntity;
}