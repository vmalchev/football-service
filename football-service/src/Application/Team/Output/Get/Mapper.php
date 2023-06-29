<?php

namespace Sportal\FootballApi\Application\Team\Output\Get;

use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\Team\Dto\TeamSocialDto;
use Sportal\FootballApi\Application\Venue;
use Sportal\FootballApi\Domain\Team\ITeamEntity;


class Mapper
{
    private Country\Output\Get\Mapper $countryMapper;

    private Venue\Output\Get\Mapper $venueMapper;

    /**
     * Mapper constructor.
     * @param Country\Output\Get\Mapper $countryMapper
     * @param Venue\Output\Get\Mapper $venueMapper
     */
    public function __construct(Country\Output\Get\Mapper $countryMapper,
                                Venue\Output\Get\Mapper $venueMapper)
    {
        $this->countryMapper = $countryMapper;
        $this->venueMapper = $venueMapper;
    }

    public function map(?ITeamEntity $teamEntity): ?Dto
    {
        if (is_null($teamEntity)) {
            return null;
        }

        $social = $teamEntity->getSocial();

        return new Dto(
            $teamEntity->getId(),
            $teamEntity->getName(),
            $teamEntity->getThreeLetterCode(),
            $teamEntity->getShortName(),
            $teamEntity->getType(),
            $this->countryMapper->map($teamEntity->getCountry()),
            $this->venueMapper->map($teamEntity->getVenue()),
            $social ?
                new TeamSocialDto(
                    $social->getWeb(), $social->getTwitterId(), $social->getFacebookId(), $social->getInstagramId(),
                    $social->getWikipediaId()
                ) : null,
            $teamEntity->getProfile() ? $teamEntity->getProfile()->getFounded() : null,
            $teamEntity->getGender() != null ? $teamEntity->getGender()->getValue() : null
        );
    }

}