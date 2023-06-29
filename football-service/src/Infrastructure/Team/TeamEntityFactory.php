<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Application\Team\Input\TeamEditDto;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntityFactory;
use Sportal\FootballApi\Domain\Team\ITeamProfileEntity;
use Sportal\FootballApi\Domain\Team\ITeamSocialEntity;
use Sportal\FootballApi\Domain\Team\TeamGender;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;
use Sportal\FootballApi\Infrastructure\President\PresidentEntity;
use Sportal\FootballApi\Infrastructure\Venue\VenueEntity;

class TeamEntityFactory implements ITeamEntityFactory
{

    private ?string $id = null;
    private string $name;
    private ?string $threeLetterCode;
    private ?string $shortName = null;
    private ?bool $isNationalType;
    private ?bool $isUndecided;
    private ?string $countryId = null;
    private ?CountryEntity $country = null;
    private ?string $venueId = null;
    private ?VenueEntity $venue = null;
    private ?string $presidentId = null;
    private ?PresidentEntity $president = null;
    private ?TeamSocialEntity $social = null;
    private ?TeamProfileEntity $profile = null;
    private ?ITeamColorsEntity $teamColors = null;
    private ?TeamGender $gender = null;

    public function setEntity(ITeamEntity $entity): ITeamEntityFactory
    {
        $factory = new TeamEntityFactory();
        $factory->setId($entity->getId());
        $factory->setName($entity->getName());
        $factory->setThreeLetterCode($entity->getThreeLetterCode());
        $factory->setShortName($entity->getShortName());
        $factory->setIsUndecided($entity->isUndecided());
        $factory->setIsNational($entity->isNational());
        $factory->setCountry($entity->getCountry());
        $factory->setVenue($entity->getVenue());
        $factory->setPresident($entity->getPresident());
        $factory->setSocial($entity->getSocial());
        $factory->setProfile($entity->getProfile());
        $factory->setTeamColors($entity->getColorsEntity());
        $factory->setGender($entity->getGender());

        return $factory;
    }

    public function setEmpty(): ITeamEntityFactory
    {
        return new TeamEntityFactory();
    }

    public function setId(string $id): ITeamEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): ITeamEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    public function setThreeLetterCode(?string $threeLetterCode): ITeamEntityFactory
    {
        $this->threeLetterCode = $threeLetterCode;
        return $this;
    }

    public function setShortName(?string $shortName): ITeamEntityFactory
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function setCountryId(?string $countryId): ITeamEntityFactory
    {
        $this->countryId = $countryId;
        return $this;
    }

    public function setVenueId(?string $venueId): ITeamEntityFactory
    {
        $this->venueId = $venueId;
        return $this;
    }

    public function setPresident(?IPresidentEntity $president): ITeamEntityFactory
    {
        $this->president = $president;
        return $this;
    }

    public function setPresidentId(?string $presidentId): ITeamEntityFactory
    {
        $this->presidentId = $presidentId;
        return $this;
    }

    public function setCountry(?ICountryEntity $countryEntity): ITeamEntityFactory
    {
        $this->country = $countryEntity;
        return $this;
    }

    public function setVenue(?IVenueEntity $venue): ITeamEntityFactory
    {
        $this->venue = $venue;
        return $this;
    }

    public function setSocial(?ITeamSocialEntity $socialEntity): ITeamEntityFactory
    {
        $this->social = $socialEntity;
        return $this;
    }

    public function setProfile(?ITeamProfileEntity $profileEntity): ITeamEntityFactory
    {
        $this->profile = $profileEntity;
        return $this;
    }

    public function setIsUndecided(?bool $isUndecided): ITeamEntityFactory
    {
        $this->isUndecided = $isUndecided;
        return $this;
    }

    public function setIsNational(?bool $isNational): ITeamEntityFactory
    {
        $this->isNationalType = $isNational;
        return $this;
    }

    public function setTeamColors(?ITeamColorsEntity $teamColors): ITeamEntityFactory
    {
        $this->teamColors = $teamColors;
        return $this;
    }

    public function setGender(?TeamGender $gender): ITeamEntityFactory
    {
        $this->gender = $gender;
        return $this;
    }

    public function create(): ITeamEntity
    {
        return new TeamEntity(
            $this->id,
            $this->name,
            $this->threeLetterCode,
            $this->shortName,
            $this->isNationalType,
            $this->isUndecided,
            $this->countryId,
            $this->venueId,
            $this->presidentId,
            $this->country,
            $this->venue,
            $this->president,
            $this->social,
            $this->profile,
            $this->teamColors,
            $this->gender);
    }
}