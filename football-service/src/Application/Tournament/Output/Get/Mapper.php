<?php


namespace Sportal\FootballApi\Application\Tournament\Output\Get;


use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Application\Country;


class Mapper
{
    /**
     * @var Country\Output\Get\Mapper
     */
    private Country\Output\Get\Mapper $countryMapper;

    public function __construct(Country\Output\Get\Mapper $countryMapper)
    {
        $this->countryMapper = $countryMapper;
    }

    public function map(?ITournamentEntity $tournamentEntity): ?Dto
    {
        if (is_null($tournamentEntity)) {
            return null;
        }

        return new Dto(
            $tournamentEntity->getId(),
            $tournamentEntity->getName(),
            $this->countryMapper->map($tournamentEntity->getCountry()),
            $tournamentEntity->getGender() != null ? $tournamentEntity->getGender()->getKey() : null,
            $tournamentEntity->getRegion() != null ? $tournamentEntity->getRegion()->getKey() : null,
            $tournamentEntity->getType() != null ? $tournamentEntity->getType()->getKey() : null
        );
    }
}