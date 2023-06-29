<?php


namespace Sportal\FootballApi\Domain\LineupPlayerType;


interface ILineupPlayerTypeRepository
{
    public function findAll(): ILineupPlayerTypeCollection;
}