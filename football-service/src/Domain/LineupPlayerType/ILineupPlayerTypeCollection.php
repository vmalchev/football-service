<?php


namespace Sportal\FootballApi\Domain\LineupPlayerType;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;

interface ILineupPlayerTypeCollection
{
    /**
     * @return ILineupPlayerTypeEntity[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return ILineupPlayerTypeEntity
     */
    public function getById(string $id): ?ILineupPlayerTypeEntity;
}