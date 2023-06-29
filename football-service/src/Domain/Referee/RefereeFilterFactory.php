<?php


namespace Sportal\FootballApi\Domain\Referee;


class RefereeFilterFactory
{
    private ?array $seasonIds = null;

    public function getFactory(): RefereeFilterFactory
    {
        return new RefereeFilterFactory();
    }

    /**
     * @param array|null $seasonIds
     * @return RefereeFilterFactory
     */
    public function setSeasonIds(?array $seasonIds): RefereeFilterFactory
    {
        $this->seasonIds = $seasonIds;
        return $this;
    }

    public function create(): RefereeFilter
    {
        return new RefereeFilter($this->seasonIds);
    }
}