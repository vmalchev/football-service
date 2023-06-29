<?php


namespace Sportal\FootballApi\Application\Venue\Input\ListAll;


use Sportal\FootballApi\Domain\Venue\VenueFilter;
use Sportal\FootballApi\Domain\Venue\VenueFilterFactory;

class Mapper
{
    private VenueFilterFactory $factory;

    /**
     * Mapper constructor.
     * @param VenueFilterFactory $factory
     */
    public function __construct(VenueFilterFactory $factory)
    {
        $this->factory = $factory;
    }

    public function map(Dto $dto): VenueFilter
    {
        return $this->factory->getFactory()
            ->setSeasonIds($dto->getSeasonIds())
            ->create();
    }

}