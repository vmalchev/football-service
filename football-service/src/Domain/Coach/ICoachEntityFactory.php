<?php


namespace Sportal\FootballApi\Domain\Coach;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Infrastructure\Coach\CoachEntityFactory;

interface ICoachEntityFactory
{
    public function setEntity(ICoachEntity $coachEntity): ICoachEntityFactory;

    public function setEmpty(): ICoachEntityFactory;

    public function setId(string $id): ICoachEntityFactory;

    public function setName(string $name): ICoachEntityFactory;

    public function setCountryId(string $countryId): ICoachEntityFactory;

    public function setCountry(ICountryEntity $countryEntity): ICoachEntityFactory;

    public function setBirthdate(?DateTimeInterface $birthdate): ICoachEntityFactory;

    public function create(): ICoachEntity;

    public function createFromArray(array $data): ICoachEntity;

    public function setGender(?PersonGender $gender): ICoachEntityFactory;
}