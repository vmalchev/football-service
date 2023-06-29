<?php

namespace Sportal\FootballApi\Domain\TournamentGroupSelection;

interface ITournamentGroupSelectionModel
{

    public function setCode(string $code): TournamentGroupSelectionModel;

    public function setEntities(array $entities): TournamentGroupSelectionModel;

    public function setDate(\DateTimeInterface $date): TournamentGroupSelectionModel;

    public function insert();
}