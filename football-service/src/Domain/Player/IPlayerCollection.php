<?php


namespace Sportal\FootballApi\Domain\Player;


interface IPlayerCollection
{
    /**
     * @return IPlayerEntity[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return IPlayerEntity|null
     */
    public function getById(string $id): ?IPlayerEntity;
}