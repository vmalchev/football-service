<?php


namespace Sportal\FootballApi\Domain\Tournament;


use Sportal\FootballApi\Infrastructure\Tournament\TournamentEntity;

interface ITournamentRepository
{
    public function exists(string $id): bool;

    public function findById($id): ?TournamentEntity;
}