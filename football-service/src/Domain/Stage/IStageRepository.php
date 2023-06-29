<?php


namespace Sportal\FootballApi\Domain\Stage;


interface IStageRepository
{
    public function findById(string $id): ?IStageEntity;

    public function findBySeasonId(string $id): array;

    /**
     * @param StageFilter $filter
     * @return IStageEntity[]
     */
    public function findByFilter(StageFilter $filter): array;

    public function insert(IStageEntity $stageEntity): IStageEntity;

    public function update(IStageEntity $stageEntity): void;

    public function delete($id): void;

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;
}