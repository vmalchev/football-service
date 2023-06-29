<?php


namespace Sportal\FootballApi\Domain\Referee;


use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;

interface IRefereeEntityFactory
{
    public function setEntity(IRefereeEntity $entity): IRefereeEntityFactory;

    public function setEmpty(): IRefereeEntityFactory;

    public function setId(string $id): IRefereeEntityFactory;

    public function setName(string $name): IRefereeEntityFactory;

    public function setCountryId(string $countryId): IRefereeEntityFactory;

    public function setCountry(ICountryEntity $countryEntity): IRefereeEntityFactory;

    public function setBirthdate(?\DateTimeInterface $birthdate): IRefereeEntityFactory;

    public function setActive(?bool $active): IRefereeEntityFactory;

    public function setGender(?PersonGender $gender): IRefereeEntityFactory;

    public function createFromArray(array $data): IRefereeEntity;

    public function create(): IRefereeEntity;
}