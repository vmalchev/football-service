<?php


namespace Sportal\FootballApi\Domain\Entity;


interface IEntityExistsRepository
{
    public function exists($tableName, $id): bool;
}