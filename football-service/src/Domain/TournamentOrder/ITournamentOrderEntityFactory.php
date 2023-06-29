<?php

namespace Sportal\FootballApi\Domain\TournamentOrder;

use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Infrastructure\TournamentOrder\TournamentOrderEntityFactory;

interface ITournamentOrderEntityFactory
{

    public function create(): ITournamentOrderEntity;

    public function setSortorder(int $sortorder): TournamentOrderEntityFactory;

    public function setClientCode(string $client_code): TournamentOrderEntityFactory;

    public function setTournamentId(string $tournament_id): TournamentOrderEntityFactory;

    public function setTournamentEntity(?ITournamentEntity $tournamentEntity): TournamentOrderEntityFactory;

}