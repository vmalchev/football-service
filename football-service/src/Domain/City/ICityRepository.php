<?php


namespace Sportal\FootballApi\Domain\City;


interface ICityRepository
{
    /**
     * @return ICityEntity[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return ICityEntity|null
     */
    public function findById(string $id): ?ICityEntity;

    /**
     * @param ICityEntity $city
     * @return ICityEntity
     */
    public function insert(ICityEntity $city): ICityEntity;

    /**
     * @param ICityEntity $city
     * @return ICityEntity
     */
    public function update(ICityEntity $city): ?ICityEntity;

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * @param string $name
     * @param string $country_id
     * @return ICityEntity|null
     */
    public function findByUniqueConstraint(string $name, string $country_id): ?ICityEntity;
}