<?php

namespace Sportal\FootballApi\Domain\TournamentOrder;



use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;

interface ITournamentOrderEntity
{

    public function getTournamentId(): string;

    public function getClientCode(): string;

    public function getSortorder(): int;

    public function getTournamentEntity(): ?ITournamentEntity;

}