<?php


namespace Sportal\FootballApi\Domain\Tournament;

use Sportal\FootballApi\Domain\Country\ICountryEntity;

interface ITournamentEntityFactory
{
    public function setId(string $id): ITournamentEntityFactory;

    public function setName(string $name): ITournamentEntityFactory;

    public function setCountry(ICountryEntity $country): ITournamentEntityFactory;

    public function setCountryId(string $countryId): ITournamentEntityFactory;

    public function setRegionalLeague(bool $regionalLeague): ITournamentEntityFactory;

    public function setEmpty(): ITournamentEntityFactory;

    public function create(): ITournamentEntity;

    public function setType(?TournamentType $type): ITournamentEntityFactory;

    public function setRegion(?TournamentRegion $region): ITournamentEntityFactory;

    public function setGender(?TournamentGender $gender): ITournamentEntityFactory;
}