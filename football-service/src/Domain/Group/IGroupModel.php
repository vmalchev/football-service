<?php


namespace Sportal\FootballApi\Domain\Group;


interface IGroupModel
{

    public function setGroups(array $groupEntities): IGroupModel;

    public function getGroups(): array;

    public function withBlacklist(): IGroupModel;

    public function upsert(): IGroupModel;

    public function delete();

}