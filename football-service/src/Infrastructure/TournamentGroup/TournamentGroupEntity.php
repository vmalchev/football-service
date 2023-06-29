<?php

namespace Sportal\FootballApi\Infrastructure\TournamentGroup;

use Sportal\FootballApi\Domain\TournamentGroup\ITournamentGroupEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class TournamentGroupEntity implements ITournamentGroupEntity, IDatabaseEntity
{

    private string $code;

    private string $name;

    private ?string $description;

    public function __construct(string $code, string $name, ?string $description)
    {
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TournamentGroupTableMapper::FIELD_CODE => $this->getCode(),
            TournamentGroupTableMapper::FIELD_NAME => $this->getName(),
            TournamentGroupTableMapper::FIELD_DESCRIPTION => $this->getDescription()
        ];
    }

    public function getPrimaryKey(): array
    {
        return [
            TournamentGroupTableMapper::FIELD_CODE => $this->code
        ];
    }
}