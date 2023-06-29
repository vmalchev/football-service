<?php

namespace Sportal\FootballApi\Application\TournamentGroupSelection\Input\Insert;

use Sportal\FootballApi\Application\IDto;

class CollectionDto implements IDto
{

    private string $code;

    private string $date;

    private array $matches;

    public function __construct(string $code, string $date, array $matches)
    {
        $this->code = $code;
        $this->date = $date;
        $this->matches = $matches;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function getMatches(): array
    {
        return $this->matches;
    }

}