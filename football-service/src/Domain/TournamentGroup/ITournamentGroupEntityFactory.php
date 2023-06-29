<?php

namespace Sportal\FootballApi\Domain\TournamentGroup;



interface ITournamentGroupEntityFactory
{

    public function setCode(string $code): ITournamentGroupEntityFactory;

    public function create(): ITournamentGroupEntity;

    public function setName(string $name): ITournamentGroupEntityFactory;

    public function setDescription(?string $description): ITournamentGroupEntityFactory;

}