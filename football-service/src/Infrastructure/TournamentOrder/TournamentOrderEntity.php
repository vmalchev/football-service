<?php

namespace Sportal\FootballApi\Infrastructure\TournamentOrder;

use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Domain\TournamentOrder\ITournamentOrderEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class TournamentOrderEntity implements ITournamentOrderEntity, IDatabaseEntity
{

    private string $client_code;

    private string $tournament_id;

    private int $sortorder;

    private ?ITournamentEntity $tournamentEntity;

    public function __construct(string $client_code,
                                string $tournament_id,
                                int $sortorder,
                                ?ITournamentEntity $tournamentEntity)
    {
        $this->client_code = $client_code;
        $this->tournament_id = $tournament_id;
        $this->sortorder = $sortorder;
        $this->tournamentEntity = $tournamentEntity;
    }

    public function getSortorder(): int
    {
        return $this->sortorder;
    }

    public function getTournamentId(): string
    {
        return $this->tournament_id;
    }

    public function getClientCode(): string
    {
        return $this->client_code;
    }

    public function getTournamentEntity(): ?ITournamentEntity
    {
        return $this->tournamentEntity;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TournamentOrderTableMapper::FIELD_CLIENT_CODE => $this->getClientCode(),
            TournamentOrderTableMapper::FIELD_TOURNAMENT_ID => $this->getTournamentId(),
            TournamentOrderTableMapper::FIELD_SORTORDER => $this->getSortorder()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            TournamentOrderTableMapper::FIELD_CLIENT_CODE => $this->getClientCode(),
            TournamentOrderTableMapper::FIELD_TOURNAMENT_ID => $this->getTournamentId()
        ];
    }

}