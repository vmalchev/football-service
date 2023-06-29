<?php


namespace Sportal\FootballApi\Domain\Player;

use DateTimeInterface;
use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Infrastructure\Player\PlayerEntity;

interface IPlayerEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getCountry(): ?ICountryEntity;

    public function getBirthdate(): ?DateTimeInterface;

    public function getBirthCity(): ?ICityEntity;

    public function getActive(): ?bool;

    public function getProfile(): ?IPlayerProfile;

    public function getSocial(): ?IPlayerSocialEntity;

    public function getPosition(): ?PlayerPosition;

    /**
     * @return PersonGender|null
     */
    public function getGender(): ?PersonGender;
}