<?php


namespace Sportal\FootballApi\Domain\City;


use Sportal\FootballApi\Domain\Country\ICountryEntity;

interface ICityEntity
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

}