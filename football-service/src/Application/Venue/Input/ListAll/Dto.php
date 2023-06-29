<?php


namespace Sportal\FootballApi\Application\Venue\Input\ListAll;


use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{
    private ?array $seasonIds = null;

    /**
     * @param array|null $seasonIds
     * @return Dto
     */
    public function setSeasonIds(?array $seasonIds): Dto
    {
        $this->seasonIds = $seasonIds;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }
}