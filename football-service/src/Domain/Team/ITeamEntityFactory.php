<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;

interface ITeamEntityFactory
{
    public function setEntity(ITeamEntity $entity): ITeamEntityFactory;

    public function setEmpty(): ITeamEntityFactory;

    public function setId(string $id): ITeamEntityFactory;

    public function setName(string $name): ITeamEntityFactory;

    public function setThreeLetterCode(?string $threeLetterCode): ITeamEntityFactory;

    public function setShortName(?string $shortName): ITeamEntityFactory;

    public function setCountryId(?string $countryId): ITeamEntityFactory;

    public function setCountry(?ICountryEntity $countryEntity): ITeamEntityFactory;

    public function setVenue(?IVenueEntity $venue): ITeamEntityFactory;

    public function setVenueId(?string $venueId): ITeamEntityFactory;

    public function setPresident(?IPresidentEntity $president): ITeamEntityFactory;

    public function setPresidentId(?string $presidentId): ITeamEntityFactory;

    public function setSocial(?ITeamSocialEntity $socialEntity): ITeamEntityFactory;

    public function setProfile(?ITeamProfileEntity $profileEntity): ITeamEntityFactory;

    public function setIsUndecided(bool $isUndecided): ITeamEntityFactory;

    public function setIsNational(bool $isNational): ITeamEntityFactory;

    public function setTeamColors(?ITeamColorsEntity $teamColors): ITeamEntityFactory;

    public function setGender(?TeamGender $gender): ITeamEntityFactory;

    public function create(): ITeamEntity;
}