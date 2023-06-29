<?php

namespace Sportal\FootballApi\Application\TournamentGroup\Input\Get;

use Sportal\FootballApi\Application\IDto;

class Dto implements IDto, \JsonSerializable
{

    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}