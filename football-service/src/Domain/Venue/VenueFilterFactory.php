<?php


namespace Sportal\FootballApi\Domain\Venue;


class VenueFilterFactory
{
    private ?array $seasonIds = null;

    public function getFactory(): VenueFilterFactory
    {
        return new VenueFilterFactory();
    }

    /**
     * @param array|null $seasonIds
     * @return VenueFilterFactory
     */
    public function setSeasonIds(?array $seasonIds): VenueFilterFactory
    {
        $this->seasonIds = $seasonIds;
        return $this;
    }

    public function create(): VenueFilter
    {
        return new VenueFilter($this->seasonIds);
    }

}