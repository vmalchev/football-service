<?php


namespace Sportal\FootballApi\Infrastructure\Coach;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class CoachEntity extends GeneratedIdDatabaseEntity implements ICoachEntity
{
    private ?string $id;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country;
    private ?DateTimeInterface $birthdate;
    private ?PersonGender $gender;

    /**
     * @param string|null $id
     * @param string $name
     * @param string $countryId
     * @param ICountryEntity|null $country
     * @param DateTimeInterface|null $birthdate
     * @param PersonGender|null $gender
     */
    public function __construct(
        ?string $id,
        string $name,
        string $countryId,
        ?ICountryEntity $country,
        ?DateTimeInterface $birthdate,
        ?PersonGender $gender
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->countryId = $countryId;
        $this->country = $country;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
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

    public function withId(
        string $id
    ): GeneratedIdDatabaseEntity {
        $updated = clone $this;
        $updated->id = $id;
        return $updated;
    }

    public function getDatabaseEntry(): array
    {
        return [
            CoachTable::FIELD_NAME => $this->getName(),
            CoachTable::FIELD_COUNTRY_ID => $this->getCountryId(),
            CoachTable::FIELD_BIRTHDATE => !is_null($this->getBirthdate()) ? $this->getBirthdate()->format(
                "Y-m-d"
            ) : null,
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
     * @return DateTimeInterface|null
     */
    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    /**
     * @inheritDoc
     */
    public function getGender(): ?PersonGender
    {
        return $this->gender;
    }
}