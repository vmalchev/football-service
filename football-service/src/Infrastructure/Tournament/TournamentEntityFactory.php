<?php


namespace Sportal\FootballApi\Infrastructure\Tournament;

use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntityFactory;
use Sportal\FootballApi\Domain\Tournament\TournamentGender;
use Sportal\FootballApi\Domain\Tournament\TournamentRegion;
use Sportal\FootballApi\Domain\Tournament\TournamentType;
use Sportal\FootballApi\Infrastructure\Tournament\TournamentEntity;

class TournamentEntityFactory implements ITournamentEntityFactory
{
    private ?string $id = null;

    private string $name;

    private ICountryEntity $country;

    private string $countryId;

    private bool $regionalLeague;

    private ?TournamentGender $gender = null;

    private ?TournamentRegion $region = null;

    private ?TournamentType $type = null;

    public function setFrom(ITournamentEntity $entity): ITournamentEntityFactory
    {
        $factory = new TournamentEntityFactory();
        $factory->id = $entity->getId();
        $factory->name = $entity->getName();
        $factory->country = $entity->getCountry();
        $factory->countryId = $entity->getCountryId();
        $factory->regionalLeague = $entity->getregionalLeague();
        $factory->gender = $entity->getGender();
        $factory->region = $entity->getRegion();
        $factory->type = $entity->getType();
        return $factory;
    }

    public function setEmpty(): ITournamentEntityFactory
    {
        return new TournamentEntityFactory();
    }

    /**
     * @param string $id
     * @return TournamentEntityFactory
     */
    public function setId(string $id): ITournamentEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return TournamentEntityFactory
     */
    public function setName(string $name): ITournamentEntityFactory
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param ICountryEntity $country
     * @return TournamentEntityFactory
     */
    public function setCountry(ICountryEntity $country): ITournamentEntityFactory
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @param string $countryId
     * @return TournamentEntityFactory
     */
    public function setCountryId(string $countryId): ITournamentEntityFactory
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * @param bool $regionalLeague
     * @return TournamentEntityFactory
     */
    public function setRegionalLeague(bool $regionalLeague): ITournamentEntityFactory
    {
        $this->regionalLeague = $regionalLeague;
        return $this;
    }

    public function create(): TournamentEntity
    {
        return new TournamentEntity(
            $this->id,
            $this->name,
            $this->country,
            $this->countryId,
            $this->regionalLeague,
            $this->gender,
            $this->region,
            $this->type
        );
    }

    public function setType(?TournamentType $type): ITournamentEntityFactory
    {
        $this->type = $type;
        return $this;
    }

    public function setRegion(?TournamentRegion $region): ITournamentEntityFactory
    {
        $this->region = $region;
        return $this;
    }

    public function setGender(?TournamentGender $gender): ITournamentEntityFactory
    {
        $this->gender = $gender;
        return $this;
    }
}