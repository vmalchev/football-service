<?php


namespace Sportal\FootballApi\Domain\Team;


use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;
use Sportal\FootballApi\Infrastructure\Team\TeamProfileEntity;
use Sportal\FootballApi\Infrastructure\Team\TeamSocialEntity;

interface ITeamEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getThreeLetterCode(): ?string;

    public function getShortName(): ?string;

    /**
     * @return bool|null
     * @deprecated use getType instead
     */
    public function isNational(): ?bool;

    /**
     * @return bool|null
     * @deprecated use getType instead
     */
    public function isUndecided(): ?bool;

    public function getCountry(): ?CountryEntity;

    public function getVenue(): ?IVenueEntity;

    public function getPresident(): ?IPresidentEntity;

    public function getSocial(): ?TeamSocialEntity;

    public function getProfile(): ?TeamProfileEntity;

    public function getType(): TeamType;

    public function getColorsEntity(): ?ITeamColorsEntity;

    public function getGender(): ?TeamGender;
}