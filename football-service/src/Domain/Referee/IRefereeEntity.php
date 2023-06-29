<?php


namespace Sportal\FootballApi\Domain\Referee;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;

interface IRefereeEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getCountryId(): string;

    public function getCountry(): ?ICountryEntity;

    public function getBirthdate(): ?DateTimeInterface;

    public function isActive(): ?bool;

    public function getGender(): ?PersonGender;
}