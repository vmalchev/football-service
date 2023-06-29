<?php

namespace Sportal\FootballApi\Application\Lineup\Input\Profile;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
