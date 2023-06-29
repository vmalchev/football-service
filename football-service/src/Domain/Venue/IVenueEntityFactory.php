<?php


namespace Sportal\FootballApi\Domain\Venue;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;

interface IVenueEntityFactory
{
    public function setEntity(IVenueEntity $venueEntity): IVenueEntityFactory;

    public function setEmpty(): IVenueEntityFactory;

    public function setId(string $id): IVenueEntityFactory;

    public function setName(string $name): IVenueEntityFactory;

    public function setCountryId(string $countryId): IVenueEntityFactory;

    public function setCountry(ICountryEntity $countryEntity): IVenueEntityFactory;

    public function setCityId(?string $city): IVenueEntityFactory;

    public function setCity(?ICityEntity $city): IVenueEntityFactory;

    public function setProfile(?array $profile): IVenueEntityFactory;

    public function create(): IVenueEntity;

    public function createFromArray(array $data): IVenueEntity;

}