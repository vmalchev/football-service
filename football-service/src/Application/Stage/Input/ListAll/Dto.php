<?php


namespace Sportal\FootballApi\Application\Stage\Input\ListAll;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{

    private string $seasonId;

    public function __construct(string $seasonId)
    {
        $this->seasonId = $seasonId;
    }

    /**
     * @return int
     */
    public function getSeasonId(): string
    {
        return $this->seasonId;
    }

}