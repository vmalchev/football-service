<?php


namespace Sportal\FootballApi\Infrastructure\Referee;


use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Domain\Referee\IRefereeEntity;
use Sportal\FootballApi\Domain\Referee\IRefereeEntityFactory;

class RefereeEntityFactory implements IRefereeEntityFactory
{
    private ?string $id = null;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country = null;
    private ?\DateTimeInterface $birthdate = null;
    private ?bool $active = null;
    private ?PersonGender $gender = null;

    public function setEntity(IRefereeEntity $entity): IRefereeEntityFactory
    {
        $factory = new RefereeEntityFactory();
        $factory->id = $entity->getId();
        $factory->name = $entity->getName();
        $factory->countryId = $entity->getCountryId();
        $factory->country = $entity->getCountry();
        $factory->birthdate = $entity->getBirthdate();
        $factory->active = $entity->isActive();
        $factory->gender = $entity->getGender();

        return $factory;
    }

    public function setEmpty(): IRefereeEntityFactory
    {
        return new RefereeEntityFactory();
    }

    public function setId(string $id): IRefereeEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): IRefereeEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    public function setCountry(ICountryEntity $countryEntity): IRefereeEntityFactory
    {
        $this->country = $countryEntity;
        return $this;
    }

    public function setCountryId(string $countryId): IRefereeEntityFactory
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): IRefereeEntityFactory
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function setActive(?bool $active): IRefereeEntityFactory
    {
        $this->active = $active;
        return $this;
    }

    public function setGender(?PersonGender $gender): IRefereeEntityFactory
    {
        $this->gender = $gender;
        return $this;
    }

    public function createFromArray(array $data): IRefereeEntity
    {
        $factory = new RefereeEntityFactory();
        $factory->id = $data[RefereeTable::FIELD_ID];
        $factory->name = $data[RefereeTable::FIELD_NAME];
        $factory->country = $data[RefereeTable::FIELD_COUNTRY];
        $factory->countryId = $data[RefereeTable::FIELD_COUNTRY_ID];
        $factory->birthdate = !is_null($data[RefereeTable::FIELD_BIRTHDATE]) ? new \DateTime($data[RefereeTable::FIELD_BIRTHDATE]) : null;
        $factory->active = $data[RefereeTable::FIELD_ACTIVE];
        $factory->gender = !is_null($data[RefereeTable::FIELD_GENDER]) ? new PersonGender($data[RefereeTable::FIELD_GENDER]) : null;

        return $factory->create();
    }

    public function create(): IRefereeEntity
    {
        return new RefereeEntity(
            $this->id,
            $this->name,
            $this->countryId,
            $this->country,
            $this->birthdate,
            $this->active,
            $this->gender
        );
    }
}