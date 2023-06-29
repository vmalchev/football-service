<?php

namespace Sportal\FootballApi\Infrastructure\TournamentOrder;

use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntityFactory;

class TournamentOrderEntityFactory implements ITournamentOrderEntityFactory
{

    private string $client_code;

    private string $tournament_id;

    private int $sortorder;

    private ?ITournamentEntity $tournamentEntity = null;

    public function setSortorder(int $sortorder): TournamentOrderEntityFactory
    {
        $this->sortorder = $sortorder;
        return $this;
    }

    public function setTournamentId(string $tournament_id): TournamentOrderEntityFactory
    {
        $this->tournament_id = $tournament_id;
        return $this;
    }

    public function setClientCode(string $client_code): TournamentOrderEntityFactory
    {
        $this->client_code = $client_code;
        return $this;
    }

    public function setTournamentEntity(?ITournamentEntity $tournamentEntity): TournamentOrderEntityFactory
    {
        $this->tournamentEntity = $tournamentEntity;
        return $this;
    }

    public function create(): ITournamentOrderEntity
    {
        return new TournamentOrderEntity(
            $this->client_code,
            $this->tournament_id,
            $this->sortorder,
            $this->tournamentEntity
        );
    }

}