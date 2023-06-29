<?php


namespace Sportal\FootballApi\Infrastructure\Player;

use DateTimeInterface;
use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Player\IPlayerProfile;
use Sportal\FootballApi\Domain\Player\IPlayerSocialEntity;
use Sportal\FootballApi\Domain\Player\PlayerPosition;

class PlayerEntity implements IPlayerEntity
{
    private ?string $id;
    private string $name;
    private ?ICountryEntity $country;
    private ?DateTimeInterface $birthdate;
    private ?ICityEntity $birthCity;
    private ?bool $active;
    private ?IPlayerProfile $profile;
    private ?IPlayerSocialEntity $social;
    private ?PlayerPosition $position;
    private ?PersonGender $gender;

    /**
     * PlayerEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param ICountryEntity|null $country
     * @param DateTimeInterface|null $birthdate
     * @param ICityEntity|null $birthCity
     * @param bool|null $active
     * @param IPlayerProfile|null $profile
     * @param IPlayerSocialEntity|null $social
     * @param PlayerPosition|null $position
     * @param PersonGender|null $gender
     */
    public function __construct(
        ?string $id,
        string $name,
        ?ICountryEntity $country,
        ?DateTimeInterface $birthdate,
        ?ICityEntity $birthCity,
        ?bool $active,
        ?IPlayerProfile $profile,
        ?IPlayerSocialEntity $social,
        ?PlayerPosition $position,
        ?PersonGender $gender
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->birthdate = $birthdate;
        $this->birthCity = $birthCity;
        $this->active = $active;
        $this->profile = $profile;
        $this->social = $social;
        $this->position = $position;
        $this->gender = $gender;
    }

    public static function create(array $data): IPlayerEntity
    {
        if (!is_null($data[PlayerTable::FIELD_PROFILE])) {
            $playerProfileData = json_decode($data[PlayerTable::FIELD_PROFILE]);
            $playerProfile = new PlayerProfile(
                $playerProfileData->height ?? null,
                $playerProfileData->weight ?? null
            );
        } else {
            $playerProfile = null;
        }

        $playerSocialChannel = !is_null($data[PlayerTable::FIELD_SOCIAL]) ? PlayerSocialEntity::createPlayerSocial(json_decode($data[PlayerTable::FIELD_SOCIAL], true)) : null;

        return new PlayerEntity(
            $data[PlayerTable::FIELD_ID],
            $data[PlayerTable::FIELD_NAME],
            $data[PlayerTable::FIELD_COUNTRY],
            !is_null($data[PlayerTable::FIELD_BIRTHDATE]) ? new \DateTime($data[PlayerTable::FIELD_BIRTHDATE]) : null,
            isset($data[PlayerTable::FIELD_BIRTH_CITY]) ? $data[PlayerTable::FIELD_BIRTH_CITY] : null,
            $data[PlayerTable::FIELD_ACTIVE],
            $playerProfile,
            $playerSocialChannel,
            !is_null($data[PlayerTable::FIELD_POSITION]) ? new PlayerPosition($data[PlayerTable::FIELD_POSITION]) : null,
            !is_null($data[PlayerTable::FIELD_GENDER]) ? new PersonGender($data[PlayerTable::FIELD_GENDER]) : null
        );
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
     * @return ICountryEntity|null
     */
    public function getCountry(): ?ICountryEntity
    {
        return $this->country;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    /**
     * @return ICityEntity|null
     */
    public function getBirthCity(): ?ICityEntity
    {
        return $this->birthCity;
    }

    /**
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return IPlayerProfile|null
     */
    public function getProfile(): ?IPlayerProfile
    {
        return $this->profile;
    }

    /**
     * @return IPlayerSocialEntity|null
     */
    public function getSocial(): ?IPlayerSocialEntity
    {
        return $this->social;
    }

    /**
     * @return PlayerPosition|null
     */
    public function getPosition(): ?PlayerPosition
    {
        return $this->position;
    }

    /**
     * @return PersonGender|null
     */
    public function getGender(): ?PersonGender
    {
        return $this->gender;
    }

}