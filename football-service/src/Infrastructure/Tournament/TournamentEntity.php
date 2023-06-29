<?php


namespace Sportal\FootballApi\Infrastructure\Tournament;

use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Tournament\ITournamentEntity;
use Sportal\FootballApi\Domain\Tournament\TournamentGender;
use Sportal\FootballApi\Domain\Tournament\TournamentRegion;
use Sportal\FootballApi\Domain\Tournament\TournamentType;

class TournamentEntity implements ITournamentEntity
{
    private ?string $id;

    private string $name;

    private ICountryEntity $country;

    private ?string $countryId;

    private ?bool $regionalLeague;

    private ?TournamentGender $gender;

    private ?TournamentRegion $region;

    private ?TournamentType $type;

    /**
     * SeasonEntity constructor.
     * @param string $id
     * @param string $name
     * @param ICountryEntity $country
     * @param string|null $countryId
     * @param bool|null $regionalLeague
     * @param TournamentGender|null $gender
     * @param TournamentRegion|null $region
     * @param TournamentType|null $type
     */
    public function __construct(
        string $id,
        string $name,
        ICountryEntity $country,
        ?string $countryId,
        ?bool $regionalLeague,
        ?TournamentGender $gender,
        ?TournamentRegion $region,
        ?TournamentType $type
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->countryId = $countryId;
        $this->regionalLeague = $regionalLeague;
        $this->gender = $gender;
        $this->region = $region;
        $this->type = $type;
    }


    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ICountryEntity
     */
    public function getCountry(): ICountryEntity
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->countryId;
    }

    /**
     * @return bool
     */
    public function getRegionalLeague(): ?bool
    {
        return $this->regionalLeague;
    }

    /**
     * @return TournamentGender|null
     */
    public function getGender(): ?TournamentGender
    {
        return $this->gender;
    }

    /**
     * @return TournamentRegion|null
     */
    public function getRegion(): ?TournamentRegion
    {
        return $this->region;
    }

    /**
     * @return TournamentType|null
     */
    public function getType(): ?TournamentType
    {
        return $this->type;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TournamentTableMapper::FIELD_ID => $this->id,
            TournamentTableMapper::FIELD_NAME => $this->name,
            TournamentTableMapper::FIELD_COUNTRY_ID => $this->countryId,
            TournamentTableMapper::FIELD_REGIONAL_LEAGUE => $this->regionalLeague,
            TournamentTableMapper::FIELD_GENDER => $this->gender,
            TournamentTableMapper::FIELD_REGION => $this->region,
            TournamentTableMapper::FIELD_TYPE => $this->type
        ];
    }
}