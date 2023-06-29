<?php

namespace Sportal\FootballApi\Domain\Player;


interface IPlayerRepository
{
    /**
     * @return IPlayerEntity[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return IPlayerEntity|null
     */
    public function findById(string $id): ?IPlayerEntity;

    public function insert(IPlayerEditEntity $player): IPlayerEditEntity;

    public function update(IPlayerEditEntity $player): IPlayerEntity;

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * @param array $ids
     * @return IPlayerCollection
     */
    public function findByIds(array $ids): IPlayerCollection;
}