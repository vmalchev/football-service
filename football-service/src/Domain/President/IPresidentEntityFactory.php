<?php


namespace Sportal\FootballApi\Domain\President;


interface IPresidentEntityFactory
{
    public function setEntity(IPresidentEntity $cityEntity): IPresidentEntityFactory;

    public function setEmpty(): IPresidentEntityFactory;

    public function setId(string $id): IPresidentEntityFactory;

    public function setName(string $name): IPresidentEntityFactory;

    public function create(): IPresidentEntity;

    public function createFromArray(array $data): IPresidentEntity;
}