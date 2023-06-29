<?php


namespace Sportal\FootballApi\Application\Group\Input\Destroy;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{

    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

}