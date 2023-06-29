<?php


namespace Sportal\FootballApi\Infrastructure\City;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\City\ICityEntityFactory;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;

class CityEntityFactory implements ICityEntityFactory
{
    private ?string $id = null;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country = null;

    public function setEntity(ICityEntity $cityEntity): ICityEntityFactory
    {
        $factory = new CityEntityFactory();
        $factory->id = $cityEntity->getId();
        $factory->name = $cityEntity->getName();
        $factory->countryId = $cityEntity->getCountryId();
        $factory->country = $cityEntity->getCountry();
        return $factory;
    }

    public function setEmpty(): ICityEntityFactory
    {
        return new CityEntityFactory();
    }

    public function setId(string $id): ICityEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): ICityEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    public function setCountryId(string $countryId): ICityEntityFactory
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function setCountry(?ICountryEntity $countryEntity): ICityEntityFactory
    {
        $this->country = $countryEntity;
        return $this;
    }

    public function createFromArray(array $data): ICityEntity
    {
        $factory = new CityEntityFactory();
        $factory->id = $data[CityTable::FIELD_ID];
        $factory->name = $data[CityTable::FIELD_NAME];
        $factory->country = $data[CityTable::FIELD_COUNTRY] ?? null;
        $factory->countryId = $data[CityTable::FIELD_COUNTRY_ID];

        return $factory->create();
    }

    public function create(): ICityEntity
    {
        return new CityEntity($this->id, $this->name, $this->countryId, $this->country);
    }


}