<?php


namespace Sportal\FootballApi\Domain\Group;


interface IGroupEntityFactory
{
    public function setEmpty(): IGroupEntityFactory;

    public function setId(?string $id): IGroupEntityFactory;

    public function setName(string $name): IGroupEntityFactory;

    public function setStageId(string $stageId): IGroupEntityFactory;

    public function setSortorder(?int $sortorder): IGroupEntityFactory;

    public function create(): IGroupEntity;
}