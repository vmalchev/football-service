<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroupSelection;

use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionEntityFactory;

class TournamentGroupSelectionEntityFactory implements ITournamentGroupSelectionEntityFactory
{

    private string $code;

    private \DateTimeInterface $date;

    private string $matchId;

    /**
     * @inheritDoc
     */
    public function setCode(string $code): TournamentGroupSelectionEntityFactory
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setDate(\DateTimeInterface $date): TournamentGroupSelectionEntityFactory
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMatchId(string $matchId): TournamentGroupSelectionEntityFactory
    {
        $this->matchId = $matchId;
        return $this;
    }

    public function create(): TournamentGroupSelectionEntity
    {
        return new TournamentGroupSelectionEntity($this->code, $this->date, $this->matchId);
    }

}