<?php

namespace Sportal\FootballApi\Domain\Venue;


interface IVenueRepository
{
    public function insert(IVenueEntity $venue): IVenueEntity;

    public function update(IVenueEntity $venue): IVenueEntity;

    /**
     * @return IVenueEntity[]
     */
    public function findAll(VenueFilter $filter): array;

    /**
     * @param string $id
     * @return IVenueEntity|null
     */
    public function findById(string $id): ?IVenueEntity;

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * @param string $name
     * @param string $country_id
     * @param string|null $city_id
     * @return IVenueEntity|null
     */
    public function findByUniqueConstraint(string $name, string $country_id, ?string $city_id): ?IVenueEntity;
}