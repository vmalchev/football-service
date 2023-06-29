<?php


namespace Sportal\FootballApi\Application\Coach\Output\Get;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Application\Country;

class Mapper
{
    /**
     * @var Country\Output\Get\Mapper
     */
    private Country\Output\Get\Mapper $countryMapper;

    /**
     * @param Country\Output\Get\Mapper $countryMapper
     */
    public function __construct(
        Country\Output\Get\Mapper $countryMapper
    ) {
        $this->countryMapper = $countryMapper;
    }

    public function map(?ICoachEntity $coachEntity): ?Dto
    {
        if (is_null($coachEntity)) {
            return null;
        }

        return new Dto(
            $coachEntity->getId(),
            $coachEntity->getName(),
            $this->countryMapper->map($coachEntity->getCountry()),
            $coachEntity->getBirthdate() ? $coachEntity->getBirthdate()->format("Y-m-d") : null,
            $coachEntity->getGender()
        );
    }
}