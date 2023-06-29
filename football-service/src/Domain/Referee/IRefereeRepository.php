<?php


namespace Sportal\FootballApi\Domain\Referee;


interface IRefereeRepository
{
    public function insert(IRefereeEntity $referee): IRefereeEntity;

    public function update(IRefereeEntity $referee): IRefereeEntity;

    public function findAll(RefereeFilter $refereeFilter): array;

    public function findById(string $id): ?IRefereeEntity;

    public function exists(string $id): bool;
}