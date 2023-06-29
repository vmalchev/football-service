<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Team\TeamGender;
use Sportal\FootballApi\Domain\Team\TeamType;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;
use Sportal\FootballApi\Infrastructure\Venue\VenueEntityFactory;

class TeamEntity extends GeneratedIdDatabaseEntity implements ITeamEntity
{
    private ?string $id;
    private string $name;
    private ?string $threeLetterCode;
    private ?string $shortName;
    private ?bool $national;
    private ?bool $undecided;
    private ?string $countryId;
    private ?string $venueId;
    private ?string $presidentId;
    private ?CountryEntity $country;
    private ?IVenueEntity $venue;
    private ?IPresidentEntity $president;
    private ?TeamSocialEntity $social;
    private ?TeamProfileEntity $profile;
    private ?ITeamColorsEntity $colors;
    private ?TeamGender $gender;

    /**
     * @param string|null $id
     * @param string $name
     * @param string|null $threeLetterCode
     * @param string|null $shortName
     * @param bool|null $national
     * @param bool|null $undecided
     * @param string|null $countryId
     * @param string|null $venueId
     * @param string|null $presidentId
     * @param CountryEntity|null $country
     * @param IVenueEntity|null $venue
     * @param IPresidentEntity|null $president
     * @param TeamSocialEntity|null $social
     * @param TeamProfileEntity|null $profile
     * @param ITeamColorsEntity|null $colors
     * @param TeamGender|null $gender
     */
    public function __construct(
        ?string            $id,
        string             $name,
        ?string            $threeLetterCode,
        ?string            $shortName,
        ?bool              $national,
        ?bool              $undecided,
        ?string            $countryId,
        ?string            $venueId,
        ?string            $presidentId,
        ?CountryEntity     $country,
        ?IVenueEntity      $venue,
        ?IPresidentEntity  $president,
        ?TeamSocialEntity  $social,
        ?TeamProfileEntity $profile,
        ?ITeamColorsEntity $colors,
        ?TeamGender        $gender
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->threeLetterCode = $threeLetterCode;
        $this->shortName = $shortName;
        $this->national = $national;
        $this->undecided = $undecided;
        $this->countryId = $countryId;
        $this->venueId = $venueId;
        $this->presidentId = $presidentId;
        $this->country = $country;
        $this->venue = $venue;
        $this->president = $president;
        $this->social = $social;
        $this->profile = $profile;
        $this->colors = $colors;
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->threeLetterCode;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isNational(): ?bool
    {
        return $this->national;
    }

    /**
     * @return bool|null
     */
    public function isUndecided(): ?bool
    {
        return $this->undecided;
    }

    /**
     * @return CountryEntity
     */
    public function getCountry(): ?CountryEntity
    {
        return $this->country;
    }

    /**
     * @return IVenueEntity|null
     */
    public function getVenue(): ?IVenueEntity
    {
        return $this->venue;
    }

    /**
     * @return IPresidentEntity|null
     */
    public function getPresident(): ?IPresidentEntity
    {
        return $this->president;
    }

    /**
     * @return TeamSocialEntity|null
     */
    public function getSocial(): ?TeamSocialEntity
    {
        return $this->social;
    }

    /**
     * @return TeamProfileEntity|null
     */
    public function getProfile(): ?TeamProfileEntity
    {
        return $this->profile;
    }

    /**
     * @return ITeamColorsEntity|null
     */
    public function getColorsEntity(): ?ITeamColorsEntity
    {
        return $this->colors;
    }

    /**
     * @return TeamGender|null
     */
    public function getGender(): ?TeamGender
    {
        return $this->gender;
    }

    /**
     * @param string|null $id
     * @return TeamEntity
     */
    public function setId(?string $id): TeamEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return TeamType
     */
    public function getType(): TeamType
    {
        if ($this->isUndecided() === true) {
            return TeamType::PLACEHOLDER();
        } elseif ($this->isNational() === true) {
            return TeamType::NATIONAL();
        }
        return TeamType::CLUB();
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TeamTable::FIELD_NAME => $this->getName(),
            TeamTable::FIELD_THREE_LETTER_CODE => $this->threeLetterCode,
            TeamTable::FIELD_SHORT_NAME => $this->shortName,
            TeamTable::FIELD_NATIONAL => (int)$this->isNational(),
            TeamTable::FIELD_UNDECIDED => (int)$this->isUndecided(),
            TeamTable::FIELD_COUNTRY_ID => $this->countryId,
            TeamTable::FIELD_VENUE_ID => $this->venueId,
            TeamTable::FIELD_PRESIDENT_ID => $this->presidentId,
            TeamTable::FIELD_SOCIAL => !is_null($this->getSocial()) ? json_encode($this->getSocial()) : null,
            TeamTable::FIELD_PROFILE => !is_null($this->getProfile()) ? json_encode($this->getProfile()) : null,
            TeamTable::FIELD_GENDER => !is_null($this->gender) ? $this->gender->getValue() : null
        ];
    }
}