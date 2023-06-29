<?php

namespace Sportal\FootballApi\Domain\Team;


interface ITeamRepository
{
    /**
     * @return ITeamEntity[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return null|ITeamEntity
     */
    public function findById(string $id): ?ITeamEntity;

    /**
     * @param ITeamEntity $teamEditEntity
     * @return ITeamEntity
     */
    public function insert(ITeamEntity $teamEditEntity): ITeamEntity;

    /**
     * @param ITeamEntity $teamEditEntity
     * @return ITeamEntity
     */
    public function update(ITeamEntity $teamEditEntity): ITeamEntity;

    public function exists(string $id): bool;

    /**
     * @param array $ids
     * @return ITeamCollection
     */
    public function findByIds(array $ids): ITeamCollection;
}