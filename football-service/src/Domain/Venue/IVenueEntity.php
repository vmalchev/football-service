<?php


namespace Sportal\FootballApi\Domain\Venue;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;

interface IVenueEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getCountryId(): string;

    public function getCountry(): ?ICountryEntity;

    public function getCityId(): ?string;

    public function getCity(): ?ICityEntity;

    public function getProfile(): ?array;
}