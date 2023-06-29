<?php


namespace Sportal\FootballApi\Application\Team\Output\Profile;


use Sportal\FootballApi\Application\Coach;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\President;
use Sportal\FootballApi\Application\Season;
use Sportal\FootballApi\Application\Team\Dto\TeamSocialDto;
use Sportal\FootballApi\Application\Venue;
use Sportal\FootballApi\Domain\Team\ITeamProfile;

class Mapper
{
    /**
     * @var Country\Output\Get\Mapper
     */
    private Country\Output\Get\Mapper $countryMapper;

    /**
     * @var Venue\Output\Get\Mapper
     */
    private Venue\Output\Get\Mapper $venueMapper;

    /**
     * @var Coach\Output\Get\Mapper
     */
    private Coach\Output\Get\Mapper $coachMapper;

    /**
     * @var President\Output\Profile\Mapper
     */
    private President\Output\Profile\Mapper $presidentMapper;


    private Season\Output\Get\Mapper $seasonMapper;

    /**
     * @param Country\Output\Get\Mapper $countryMapper
     * @param Venue\Output\Get\Mapper $venueMapper
     * @param Coach\Output\Get\Mapper $coachMapper
     * @param President\Output\Profile\Mapper $presidentMapper
     * @param Season\Output\Get\Mapper $seasonMapper
     */
    public function __construct(
        Country\Output\Get\Mapper       $countryMapper,
        Venue\Output\Get\Mapper         $venueMapper,
        Coach\Output\Get\Mapper         $coachMapper,
        President\Output\Profile\Mapper $presidentMapper,
        Season\Output\Get\Mapper        $seasonMapper
    )
    {
        $this->countryMapper = $countryMapper;
        $this->venueMapper = $venueMapper;
        $this->coachMapper = $coachMapper;
        $this->presidentMapper = $presidentMapper;
        $this->seasonMapper = $seasonMapper;
    }

    public function map(ITeamProfile $teamProfile): ?Dto
    {
        $social = $teamProfile->getTeamEntity()->getSocial();

        return new Dto(
            $teamProfile->getTeamEntity()->getId(),
            $teamProfile->getTeamEntity()->getName(),
            $teamProfile->getTeamEntity()->getThreeLetterCode(),
            $teamProfile->getTeamEntity()->getShortName(),
            $teamProfile->getTeamEntity()->getType(),
            $teamProfile->getTeamEntity()->getProfile() ? $teamProfile->getTeamEntity()->getProfile()->getFounded() : null,
            $this->countryMapper->map($teamProfile->getTeamEntity()->getCountry()),
            $this->venueMapper->map($teamProfile->getTeamEntity()->getVenue()),
            $social ?
                new TeamSocialDto(
                    $social->getWeb(), $social->getTwitterId(), $social->getFacebookId(), $social->getInstagramId(),
                    $social->getWikipediaId()
                ) : null,
            $this->coachMapper->map($teamProfile->getCurrentCoach()),
            $this->presidentMapper->map($teamProfile->getTeamEntity()->getPresident()),
            is_null($teamProfile->getTeamEntity()->getColorsEntity()) ? null :
                $teamProfile->getTeamEntity()->getColorsEntity()->getColors(),
            $teamProfile->getTeamEntity()->getGender() != null ? $teamProfile->getTeamEntity()->getGender()->getValue() : null,
            array_map([$this->seasonMapper, 'map'], $teamProfile->getActiveSeasons())
        );
    }
}