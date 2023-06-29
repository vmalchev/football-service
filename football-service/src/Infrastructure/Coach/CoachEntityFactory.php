<?php


namespace Sportal\FootballApi\Infrastructure\Coach;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Coach\ICoachEntityFactory;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;

class CoachEntityFactory implements ICoachEntityFactory
{
    private ?string $id = null;
    private string $name;
    private string $countryId;
    private ?ICountryEntity $country = null;
    private ?\DateTimeInterface $birthdate = null;
    private ?PersonGender $gender = null;

    public function setEntity(ICoachEntity $coachEntity): ICoachEntityFactory
    {
        $factory = new CoachEntityFactory();
        $factory->id = $coachEntity->getId();
        $factory->name = $coachEntity->getName();
        $factory->countryId = $coachEntity->getCountryId();
        $factory->country = $coachEntity->getCountry();
        $factory->birthdate = $coachEntity->getBirthdate();
        $factory->gender = $coachEntity->getGender();
        return $factory;
    }

    public function setEmpty(): ICoachEntityFactory
    {
        return new CoachEntityFactory();
    }

    public function setId(string $id): ICoachEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): ICoachEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    public function setCountryId(string $countryId): ICoachEntityFactory
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function setCountry(?ICountryEntity $country): ICoachEntityFactory
    {
        $this->country = $country;
        return $this;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): ICoachEntityFactory
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function setGender(?PersonGender $gender): ICoachEntityFactory
    {
        $this->gender = $gender;
        return $this;
    }

    public function createFromArray(array $data): ICoachEntity
    {
        $factory = new CoachEntityFactory();
        $factory->id = $data[CoachTable::FIELD_ID];
        $factory->name = $data[CoachTable::FIELD_NAME];
        $factory->country = $data[CoachTable::FIELD_COUNTRY];
        $factory->countryId = $data[CoachTable::FIELD_COUNTRY_ID];
        $factory->birthdate = isset($data[CoachTable::FIELD_BIRTHDATE]) ? new \DateTime(
            $data[CoachTable::FIELD_BIRTHDATE]
        ) : null;
        $factory->gender = isset($data[CoachTable::FIELD_GENDER]) ? new PersonGender($data[CoachTable::FIELD_GENDER])
            :null;

        return $factory->create();
    }

    public function create(): ICoachEntity
    {
        return new CoachEntity(
            $this->id, $this->name, $this->countryId, $this->country, $this->birthdate, $this->gender
        );
    }


}