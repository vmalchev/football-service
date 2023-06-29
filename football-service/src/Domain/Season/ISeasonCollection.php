<?php


namespace Sportal\FootballApi\Domain\Season;


interface ISeasonCollection
{

    public function getAll(): array;

    public function getById($id): ?ISeasonEntity;
}