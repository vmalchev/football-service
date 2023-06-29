<?php


namespace Sportal\FootballApi\Domain\Season;


interface ISeasonRepository
{
    /**
     * @param SeasonFilter $filter
     * @return ISeasonEntity[]
     */
    public function listByFilter(SeasonFilter $filter): array;

    public function findById(string $id): ?ISeasonEntity;

    public function findByIds(array $ids): ISeasonCollection;

    public function exists($id): bool;

    public function insert($seasonEntity): ISeasonEntity;

    public function update($seasonEntity): ?ISeasonEntity;

    public function findByTournamentIdAndName($seasonEntity): array;
}
