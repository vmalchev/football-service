<?php


namespace Sportal\FootballApi\Infrastructure\Venue;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;

class VenueEntityFactory implements IVenueEntityFactory
{
    private ?string $id = null;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country = null;
    private ?string $cityId = null;
    private ?ICityEntity $city = null;
    private ?array $profile = null;

    /**
     * @param string|null $id
     * @return IVenueEntityFactory
     */
    public function setId(?string $id): IVenueEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return IVenueEntityFactory
     */
    public function setName(string $name): IVenueEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $countryId
     * @return IVenueEntityFactory
     */
    public function setCountryId(string $countryId): IVenueEntityFactory
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @param ICountryEntity|null $country
     * @return IVenueEntityFactory
     */
    public function setCountry(?ICountryEntity $country): IVenueEntityFactory
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string|null $cityId
     * @return IVenueEntityFactory
     */
    public function setCityId(?string $cityId): IVenueEntityFactory
    {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * @param ICityEntity|null $city
     * @return IVenueEntityFactory
     */
    public function setCity(?ICityEntity $city): IVenueEntityFactory
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @param array|null $profile
     * @return IVenueEntityFactory
     */
    public function setProfile(?array $profile): IVenueEntityFactory
    {
        $this->profile = $profile;
        return $this;
    }


    public function setEntity(IVenueEntity $venueEntity): IVenueEntityFactory
    {
        $factory = new VenueEntityFactory();
        $factory->id = $venueEntity->getId();
        $factory->name = $venueEntity->getName();
        $factory->countryId = $venueEntity->getCountryId();
        $factory->country = $venueEntity->getCountry();
        $factory->cityId = $venueEntity->getCityId();
        $factory->city = $venueEntity->getCity();
        $factory->profile = $venueEntity->getProfile();
        return $factory;
    }

    public function setEmpty(): IVenueEntityFactory
    {
        return new VenueEntityFactory();
    }

    public function createFromArray(array $data): IVenueEntity
    {
        $profileArr = !empty($data[VenueTable::FIELD_PROFILE]) ? $data[VenueTable::FIELD_PROFILE] : null;
        if (!is_null($profileArr) && !is_array($profileArr)) {
            $profileArr = json_decode($profileArr, true);
        }

        $factory = new VenueEntityFactory();
        $factory->id = $data[VenueTable::FIELD_ID];
        $factory->name = $data[VenueTable::FIELD_NAME];
        $factory->country = $data[VenueTable::FIELD_COUNTRY] ?? null;
        $factory->countryId = $data[VenueTable::FIELD_COUNTRY_ID];
        $factory->city = is_object($data[VenueTable::FIELD_CITY] ?? null) ? $data[VenueTable::FIELD_CITY] : null;
        $factory->cityId = $data[VenueTable::FIELD_CITY_ID] ?? null;
        $factory->profile = $profileArr;
        return $factory->create();
    }

    public function create(): IVenueEntity
    {
        return new VenueEntity($this->id, $this->name, $this->countryId, $this->country, $this->cityId, $this->city, $this->profile);
    }
}