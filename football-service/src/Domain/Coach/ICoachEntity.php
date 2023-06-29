<?php


namespace Sportal\FootballApi\Domain\Coach;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;

interface ICoachEntity
{
    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getCountryId(): string;

    /**
     * @return ICountryEntity|null
     */
    public function getCountry(): ?ICountryEntity;

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthdate(): ?DateTimeInterface;

    /**
     * @return PersonGender|null
     */
    public function getGender(): ?PersonGender;
}