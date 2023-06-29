<?php


namespace Sportal\FootballApi\Infrastructure\Venue;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class VenueEntity extends GeneratedIdDatabaseEntity implements IVenueEntity
{
    private ?string $id;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country;
    private ?string $cityId;
    private ?ICityEntity $city;
    private ?array $profile;

    /**
     * VenueEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string $countryId
     * @param ICountryEntity|null $country
     * @param string|null $cityId
     * @param ICityEntity|null $city
     * @param array $profile
     */
    public function __construct(
        ?string $id,
        string $name,
        string $countryId,
        ?ICountryEntity $country,
        ?string $cityId,
        ?ICityEntity $city,
        ?array $profile)
    {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->country = $country;
        $this->cityId = $cityId;
        $this->city = $city;
        $this->profile = $profile;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return ICountryEntity|null
     */
    public function getCountry(): ?ICountryEntity
    {
        return $this->country;
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $updated = clone $this;
        $updated->id = $id;
        return $updated;
    }

    public function getDatabaseEntry(): array
    {
        return [
            VenueTable::FIELD_NAME => $this->getName(),
            VenueTable::FIELD_COUNTRY_ID => $this->getCountryId(),
            VenueTable::FIELD_CITY_ID => $this->getCityId(),
            VenueTable::FIELD_PROFILE => !empty($this->getProfile()) ? json_encode($this->profile) : null
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->countryId;
    }

    /**
     * @return string|null
     */
    public function getCityId(): ?string
    {
        return $this->cityId;
    }

    /**
     * @return array|null
     */
    public function getProfile(): ?array
    {
        return $this->profile;
    }

    /**
     * @return ICityEntity|null
     */
    public function getCity(): ?ICityEntity
    {
        return $this->city;
    }
}