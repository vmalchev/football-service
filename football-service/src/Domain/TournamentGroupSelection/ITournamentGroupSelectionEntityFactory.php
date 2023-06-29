<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;

use Sportal\FootballApi\Infrastructure\TournamentGroupSelection\TournamentGroupSelectionEntity;
use Sportal\FootballApi\Infrastructure\TournamentGroupSelection\TournamentGroupSelectionEntityFactory;

interface ITournamentGroupSelectionEntityFactory
{

    /**
     * @param string $matchId
     */
    public function setMatchId(string $matchId): TournamentGroupSelectionEntityFactory;

    /**
     * @param string $code
     */
    public function setCode(string $code): TournamentGroupSelectionEntityFactory;

    /**
     * @param \DateTimeInterface $date
     * @return TournamentGroupSelectionEntityFactory
     */
    public function setDate(\DateTimeInterface $date): TournamentGroupSelectionEntityFactory;

    public function create(): TournamentGroupSelectionEntity;
}