<?php


namespace Sportal\FootballApi\Infrastructure\Referee;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Domain\Referee\IRefereeEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class RefereeEntity extends GeneratedIdDatabaseEntity implements IRefereeEntity
{
    private ?string $id;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country;
    private ?DateTimeInterface $birthdate;
    private ?bool $active;
    private ?PersonGender $gender;

    /**
     * RefereeEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string $countryId
     * @param ICountryEntity|null $country
     * @param DateTimeInterface|null $birthdate
     * @param bool|null $active
     * @param PersonGender|null $gender
     */
    public function __construct(
        ?string $id,
        string $name,
        string $countryId,
        ?ICountryEntity $country,
        ?DateTimeInterface $birthdate,
        ?bool $active,
        ?PersonGender $gender
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->country = $country;
        $this->birthdate = $birthdate;
        $this->active = $active;
        $this->gender = $gender;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

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
        $entry = [
            RefereeTable::FIELD_NAME => $this->getName(),
            RefereeTable::FIELD_COUNTRY_ID => $this->getCountryId(),
            RefereeTable::FIELD_BIRTHDATE => !is_null($this->getBirthdate()) ? $this->getBirthdate()->format("Y-m-d") : null,
            RefereeTable::FIELD_ACTIVE => !is_null($this->isActive()) ? ($this->isActive() ? 1 : 0) : null,
        ];

        return $entry;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return PersonGender|null
     */
    public function getGender(): ?PersonGender
    {
        return $this->gender;
    }

}