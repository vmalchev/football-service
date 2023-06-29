<?php


namespace Sportal\FootballApi\Domain\Tournament;

use Sportal\FootballApi\Domain\Country\ICountryEntity;


interface ITournamentEntity
{
    public function getId(): ?string;

    public function getName(): string;

    public function getCountry(): ?ICountryEntity;

    public function getCountryId(): string;

    public function getRegionalLeague(): ?bool;

    public function getGender(): ?TournamentGender;

    public function getType(): ?TournamentType;

    public function getRegion(): ?TournamentRegion;
}