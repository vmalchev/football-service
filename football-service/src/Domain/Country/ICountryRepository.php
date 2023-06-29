<?php

namespace Sportal\FootballApi\Domain\Country;


interface ICountryRepository
{
    /**
     * @param int $id
     * @return null|ICountryEntity
     */
    public function findById(int $id): ?ICountryEntity;
}