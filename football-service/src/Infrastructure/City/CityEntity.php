<?php


namespace Sportal\FootballApi\Infrastructure\City;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class CityEntity extends GeneratedIdDatabaseEntity implements ICityEntity
{
    private ?string $id;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country;

    /**
     * @param string|null $id
     * @param string $name
     * @param string $countryId
     * @param ICountryEntity|null $country
     */
    public function __construct(?string $id, string $name, string $countryId, ?ICountryEntity $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
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
            CityTable::FIELD_NAME => $this->getName(),
            CityTable::FIELD_COUNTRY_ID => $this->getCountryId(),
            CityTable::FIELD_UPDATED_AT => (new \DateTime())->format(\DateTime::ATOM)
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function getCountry(): ?ICountryEntity
    {
        return $this->country;
    }
}