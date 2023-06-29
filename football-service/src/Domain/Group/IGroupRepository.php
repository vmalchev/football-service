<?php


namespace Sportal\FootballApi\Domain\Group;


interface IGroupRepository
{
    public function findById(string $id): ?IGroupEntity;

    public function findByStageId(string $stageId): array;

    public function create(IGroupEntity $groupEntity): IGroupEntity;

    public function update(IGroupEntity $groupEntity): void;

    public function delete(string $id);

    public function exists(string $id): bool;
}