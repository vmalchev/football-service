<?php


namespace Sportal\FootballApi\Infrastructure\Player;

use DateTimeInterface;
use Sportal\FootballApi\Application\Player\Dto\PlayerEditDto;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Domain\Player\IPlayerEditEntity;
use Sportal\FootballApi\Domain\Player\IPlayerProfile;
use Sportal\FootballApi\Domain\Player\IPlayerSocialEntity;
use Sportal\FootballApi\Domain\Player\PlayerPosition;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class PlayerEditEntity extends GeneratedIdDatabaseEntity implements IPlayerEditEntity
{
    private ?string $id;
    private string $name;
    private string $country_id;
    private ?DateTimeInterface $birthdate;
    private ?string $birthCityId;
    private ?bool $active;
    private ?IPlayerProfile $profile;
    private ?IPlayerSocialEntity $social;
    private ?PlayerPosition $position;

    /**
     * PlayerEditEntity constructor.
     * @param string|null $id
     * @param string $name
     * @param string $country_id
     * @param DateTimeInterface|null $birthdate
     * @param bool|null $active
     * @param IPlayerProfile|null $profile
     * @param IPlayerSocialEntity|null $social
     * @param PlayerPosition|null $position
     */
    public function __construct(
        ?string $id,
        string $name,
        string $country_id,
        ?DateTimeInterface $birthdate,
        ?string $birthCityId,
        ?bool $active,
        ?IPlayerProfile $profile,
        ?IPlayerSocialEntity $social,
        ?PlayerPosition $position
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->country_id = $country_id;
        $this->birthdate = $birthdate;
        $this->birthCityId = $birthCityId;
        $this->active = $active;
        $this->profile = $profile;
        $this->social = $social;
        $this->position = $position;
    }

    public static function fromPlayerEditDto(PlayerEditDto $playerEditDto)
    {
        return new PlayerEditEntity(
            null,
            $playerEditDto->getName(),
            $playerEditDto->getCountryId(),
            $playerEditDto->getBirthdate(),
            $playerEditDto->getBirthCityId(),
            !is_null($playerEditDto->isActive()) ? $playerEditDto->isActive() : true,
            !empty($playerEditDto->getProfile()) ? PlayerProfile::fromPlayerProfileDto($playerEditDto->getProfile()) : null,
            !empty($playerEditDto->getSocial()) ? PlayerSocialEntity::fromPlayerSocialDto($playerEditDto->getSocial()) : null,
            !empty($playerEditDto->getPosition()) ? PlayerPosition::{$playerEditDto->getPosition()}() : null
        );
    }

    /**
     * @return string|null
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this|PlayerEditEntity
     */
    public function withId(string $id): PlayerEditEntity
    {
        $this->id = $id;
        return $this;
    }

    public function getDatabaseEntry(): array
    {
        return [
            PlayerTable::FIELD_NAME => $this->getName(),
            PlayerTable::FIELD_COUNTRY_ID => $this->getCountryId(),
            PlayerTable::FIELD_BIRTHDATE => !is_null($this->getBirthdate()) ? $this->getBirthdate()->format("Y-m-d") : null,
            PlayerTable::FIELD_BIRTH_CITY_ID => $this->getBirthCityId(),
            PlayerTable::FIELD_ACTIVE => (!is_null($this->getActive())) ? (int)$this->getActive() : null,
            PlayerTable::FIELD_PROFILE => !is_null($this->getProfile()) ? json_encode($this->getProfile()) : null,
            PlayerTable::FIELD_SOCIAL => !is_null($this->getSocial()) ? json_encode($this->getSocial()) : null,
            PlayerTable::FIELD_POSITION => !is_null($this->getPosition()) ? $this->getPosition()->getValue() : null,
        ];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getCountryId(): string
    {
        return $this->country_id;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthdate(): ?DateTimeInterface
    {
        return $this->birthdate;
    }

    /**
     * @return string|null
     */
    public function getBirthCityId(): ?string
    {
        return $this->birthCityId;
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

    public function getGender(): ?PersonGender
    {
        return null;
    }
}