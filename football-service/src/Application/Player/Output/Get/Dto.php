<?php

namespace Sportal\FootballApi\Application\Player\Output\Get;

use JsonSerializable;
use Sportal\FootballApi\Application\City;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\Player\Dto\PlayerEditSocialDto;
use Sportal\FootballApi\Application\Player\Dto\PlayerProfileDto;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Player", required={"id", "name", "country"})
 */
class Dto implements IDto, JsonSerializable, IAssetable, ITranslatable
{
    /**
     * @var string
     * @SWG\Property(property="id")
     */
    private string $id;

    /**
     * @var string
     * @SWG\Property(property="name")
     */
    private string $name;

    /**
     * @var Country\Output\Get\Dto
     * @SWG\Property(property="country")
     */
    private Country\Output\Get\Dto $country;

    /**
     * @var bool
     * @SWG\Property(property="active")
     */
    private ?bool $active;

    /**
     * @var string|null
     * @SWG\Property(property="birthdate")
     */
    private ?string $birthdate;

    /**
     * @var City\Output\Get\Dto|null
     * @SWG\Property(property="birth_city")
     */
    private ?City\Output\Get\Dto $birth_city;

    /**
     * @var PlayerProfileDto|null
     * @SWG\Property(property="profile")
     */
    private ?PlayerProfileDto $profile;

    /**
     * @var PlayerEditSocialDto|null
     * @SWG\Property(property="social")
     */
    private ?PlayerEditSocialDto $social;

    /**
     * @var string|null
     * @SWG\Property(property="position")
     */
    private ?string $position;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=PERSON_GENDER)
     */
    private ?string $gender;

    private $assets;

    /**
     * @param string $id
     * @param string $name
     * @param Country\Output\Get\Dto $country
     * @param bool|null $active
     * @param string|null $birthdate
     * @param City\Output\Get\Dto|null $birth_city
     * @param PlayerProfileDto|null $profile
     * @param PlayerEditSocialDto|null $social
     * @param string|null $position
     * @param string|null $gender
     */
    public function __construct(
        string $id,
        string $name,
        Country\Output\Get\Dto $country,
        ?bool $active,
        ?string $birthdate,
        ?City\Output\Get\Dto $birth_city,
        ?PlayerProfileDto $profile,
        ?PlayerEditSocialDto $social,
        ?string $position,
        ?string $gender
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->active = $active;
        $this->birthdate = $birthdate;
        $this->birth_city = $birth_city;
        $this->profile = $profile;
        $this->social = $social;
        $this->position = $position;
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return Country\Output\Get\Dto
     */
    public function getCountry(): Country\Output\Get\Dto
    {
        return $this->country;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return string|null
     */
    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    /**
     * @return City\Output\Get\Dto|null
     */
    public function getBirthCity(): ?City\Output\Get\Dto
    {
        return $this->birth_city;
    }

    /**
     * @return PlayerProfileDto|null
     */
    public function getProfile(): ?PlayerProfileDto
    {
        return $this->profile;
    }

    /**
     * @return PlayerEditSocialDto|null
     */
    public function getSocial(): ?PlayerEditSocialDto
    {
        return $this->social;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::PLAYER();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::PLAYER();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}
