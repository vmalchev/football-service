<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroup;

use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntity;
use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntityFactory;

class TournamentGroupEntityFactory implements ITournamentGroupEntityFactory
{

    private string $code;

    private string $name;

    private ?string $description;

    public function setDescription(?string $description): TournamentGroupEntityFactory
    {
        $this->description = $description;
        return $this;
    }

    public function setName(string $name): TournamentGroupEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    public function setCode(string $code): TournamentGroupEntityFactory
    {
        $this->code = $code;
        return $this;
    }

    public function create(): ITournamentGroupEntity
    {
        return new TournamentGroupEntity(
            $this->code,
            $this->name,
            $this->description
        );
    }

}