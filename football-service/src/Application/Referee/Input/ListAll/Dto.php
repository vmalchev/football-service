<?php


namespace Sportal\FootballApi\Application\Referee\Input\ListAll;


class Dto
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