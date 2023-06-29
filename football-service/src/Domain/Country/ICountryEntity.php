<?php

namespace Sportal\FootballApi\Domain\Country;


interface ICountryEntity
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string|null
     */
    public function getCode(): ?string;
}
