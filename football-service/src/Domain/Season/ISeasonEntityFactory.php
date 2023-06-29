<?php


namespace Sportal\FootballApi\Domain\Season;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;


interface ISeasonEntityFactory
{
    public function setFrom(ISeasonEntity $entity): ISeasonEntityFactory;

    public function setId(string $id): ISeasonEntityFactory;

    public function setName(string $name): ISeasonEntityFactory;

    public function setTournamentId(string $tournamentId): ISeasonEntityFactory;

    public function setTournament(?ITournamentEntity $tournament): ISeasonEntityFactory;

    public function setStatus(SeasonStatus $status): ISeasonEntityFactory;

    public function setEmpty(): ISeasonEntityFactory;

    public function create(): ISeasonEntity;
}