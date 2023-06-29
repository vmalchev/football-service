<?php

namespace Sportal\FootballApi\Domain\Coach;


use Sportal\FootballApi\Domain\Team\ITeamEntity;

interface ICoachRepository
{
    /**
     * @return ICoachEntity[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return ICoachEntity|null
     */
    public function findById(string $id): ?ICoachEntity;

    /**
     * @param ICoachEntity $coach
     * @return ICoachEntity
     */
    public function insert(ICoachEntity $coach): ICoachEntity;

    /**
     * @param ICoachEntity $coach
     * @return ICoachEntity
     */
    public function update(ICoachEntity $coach): ICoachEntity;

    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * @param ITeamEntity $teamEntity
     * @return ICoachEntity|null
     */
    public function findCurrentCoachByTeam(ITeamEntity $teamEntity): ?ICoachEntity;
}