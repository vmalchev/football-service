<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroupSelection;

use Sportal\FootballApi\Domain\TournamentGroupSelection\ITournamentGroupSelectionEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class TournamentGroupSelectionEntity implements ITournamentGroupSelectionEntity, IDatabaseEntity
{

    private string $code;

    private \DateTimeInterface $date;

    private string $matchId;

    public function __construct(string $code, \DateTimeInterface $date, string $matchId)
    {
        $this->code = $code;
        $this->date = $date;
        $this->matchId = $matchId;
    }

    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @inheritDoc
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TournamentGroupSelectionTableMapper::FIELD_CODE => $this->getCode(),
            TournamentGroupSelectionTableMapper::FIELD_DATE => $this->getDate()->format('Y-m-d'),
            TournamentGroupSelectionTableMapper::FIELD_MATCH_ID => $this->getMatchId()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            TournamentGroupSelectionTableMapper::FIELD_CODE => $this->getCode(),
            TournamentGroupSelectionTableMapper::FIELD_DATE => $this->getDate()->format('Y-m-d'),
            TournamentGroupSelectionTableMapper::FIELD_MATCH_ID => $this->getMatchId()
        ];
    }
}