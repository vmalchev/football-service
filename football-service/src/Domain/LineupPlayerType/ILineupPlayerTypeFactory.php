<?php


namespace Sportal\FootballApi\Domain\LineupPlayerType;


interface ILineupPlayerTypeFactory
{
    public function setEntity(ILineupPlayerTypeEntity $lineupPlayerEntity): ILineupPlayerTypeFactory;

    public function setEmpty(): ILineupPlayerTypeFactory;

    public function setId(string $id): ILineupPlayerTypeFactory;

    public function setName(string $name): ILineupPlayerTypeFactory;

    public function setCategory(string $category): ILineupPlayerTypeFactory;

    public function setCode(string $code): ILineupPlayerTypeFactory;

    public function setSortOrder(int $sortOrder): ILineupPlayerTypeFactory;

    public function create(): ILineupPlayerTypeEntity;
}