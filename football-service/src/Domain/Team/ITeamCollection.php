<?php


namespace Sportal\FootballApi\Domain\Team;


interface ITeamCollection
{
    public function getAll(): array;

    public function getById($id): ?ITeamEntity;

    public function getByIds($ids): self;
}