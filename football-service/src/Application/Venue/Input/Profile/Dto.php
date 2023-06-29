<?php


namespace Sportal\FootballApi\Application\Venue\Input\Profile;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId(): string
    {
        return $this->id;
    }
}

