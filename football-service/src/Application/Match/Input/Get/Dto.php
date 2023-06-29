<?php


namespace Sportal\FootballApi\Application\Match\Input\Get;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{
    private string $matchId;

    /**
     * Dto constructor.
     * @param string $matchId
     */
    public function __construct(string $matchId)
    {
        $this->matchId = $matchId;
    }

    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }

}