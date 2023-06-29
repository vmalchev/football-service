<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Venue;


use Sportal\FootballApi\Domain\Venue\IVenueEntity;

class Mapper
{
    public function map(?IVenueEntity $venueEntity): ?Dto
    {
        if ($venueEntity === null) {
            return null;
        }

        return new Dto($venueEntity->getId(), $venueEntity->getName());
    }
}