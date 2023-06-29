<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;

interface ITournamentGroupModel
{

    public function setEntity(ITournamentGroupEntity $entity): TournamentGroupModel;

    public function insert(): TournamentGroupModel;

    public function getEntity(): ITournamentGroupEntity;

    public function setTournaments(array $tournaments): TournamentGroupModel;

}