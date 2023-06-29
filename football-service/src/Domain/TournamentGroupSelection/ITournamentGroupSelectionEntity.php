<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;


interface ITournamentGroupSelectionEntity
{

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface;

    /**
     * @return string
     */
    public function getMatchId(): string;
}