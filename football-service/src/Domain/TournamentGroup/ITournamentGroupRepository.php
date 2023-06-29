<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;


interface ITournamentGroupRepository
{

    /**
     * @return array
     */
    public function findAll(): array;

    /**
     * @param ITournamentGroupEntity $entity
     * @return int
     */
    public function insert(ITournamentGroupEntity $entity): int;

    /**
     * @param ITournamentGroupEntity $entity
     * @return int
     */
    public function update(ITournamentGroupEntity $entity): int;

    /**
     * @param string $code
     * @return ITournamentGroupEntity|null
     */
    public function findByCode(string $code): ?ITournamentGroupEntity;

    /**
     * @param string $code
     * @return bool
     */
    public function existsByCode(string $code): bool;
}