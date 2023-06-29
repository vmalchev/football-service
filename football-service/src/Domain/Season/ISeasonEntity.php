<?php


namespace Sportal\FootballApi\Domain\Season;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;

interface ISeasonEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getTournament(): ?ITournamentEntity;

    public function getTournamentId(): string;

    public function getStatus(): SeasonStatus;
}