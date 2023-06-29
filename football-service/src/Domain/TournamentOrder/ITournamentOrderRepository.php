<?php

namespace Sportal\FootballApi\Domain\TournamentOrder;


interface ITournamentOrderRepository
{

    public function insert(ITournamentOrderEntity $entity);

    public function deleteByClientCode(string $clientCode): int;

    public function findByClientCode(string $clientCode): array;

}