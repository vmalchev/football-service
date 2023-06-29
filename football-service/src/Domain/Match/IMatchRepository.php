<?php

namespace Sportal\FootballApi\Domain\Match;

interface IMatchRepository
{
    /**
     * @param string $id
     * @return null|IMatchEntity
     */
    public function findById(string $id): ?IMatchEntity;

    /**
     * @param MatchFilter $filter
     * @return IMatchEntity[]
     */
    public function findByFilter(MatchFilter $filter): array;

    /**
     * @param string $id
     * @return bool
     */
    public function existsById(string $id): bool;

    public function insert(IMatchEntity $matchEntity): IMatchEntity;

    public function update(IMatchEntity $matchEntity): void;
}