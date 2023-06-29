<?php


namespace Sportal\FootballApi\Application\Player\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\City\Dto\CityDto;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IEventNotificationable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 * @deprecated
 */
class PlayerDto implements JsonSerializable, IAssetable, ITranslatable, IEventNotificationable
{
    /**
     * @var string
     * @SWG\Property()
     */
    private $id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * @var CountryDto
     * @SWG\Property()
     */
    private $country;

    /**
     * @var bool
     * @SWG\Property()
     */
    private $active;

    /**
     * @var string
     * @SWG\Property()
     */
    private $birthdate;

    /**
     * @var CityDto
     * @SWG\Property()
     */
    private $birth_city;

    /**
     * @var PlayerProfileDto|null
     * @SWG\Property()
     */
    private $profile;

    /**
     * @var PlayerEditSocialDto|null
     * @SWG\Property()
     */
    private $social;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $position;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=PERSON_GENDER)
     */
    private ?string $gender;

    private $assets;

    public function __construct(
        string $id,
        string $name,
        CountryDto $country,
        ?bool $active,
        ?string $birthdate = null,
        ?CityDto $birth_city = null,
        ?PlayerProfileDto $profile = null,
        ?PlayerEditSocialDto $social = null,
        ?string $position = null,
        ?string $gender
    )
    {
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

    public static function fromPlayerEntity($playerEntity, CountryDto $countryDto, ?CityDto $cityDto = null)
    {
        if (!is_null($playerEntity->getSocial())) {
            $social = new PlayerEditSocialDto(
                $playerEntity->getSocial()->getWeb(),
                $playerEntity->getSocial()->getTwitterId(),
                $playerEntity->getSocial()->getFacebookId(),
                $playerEntity->getSocial()->getInstagramId(),
                $playerEntity->getSocial()->getWikipediaId(),
                $playerEntity->getSocial()->getYoutubeChannelId()
            );
        } else {
            $social = null;
        }

        if (!is_null($playerEntity->getProfile())) {
            $profile = new PlayerProfileDto(
                $playerEntity->getProfile()->getHeight(),
                $playerEntity->getProfile()->getWeight(),
            );
        } else {
            $profile = null;
        }

        return new PlayerDto(
            $playerEntity->getId(),
            $playerEntity->getName(),
            $countryDto,
            $playerEntity->getActive(),
            !is_null($playerEntity->getBirthdate()) ? $playerEntity->getBirthdate()->format("Y-m-d") : null,
            $cityDto,
            $profile,
            $social,
            !is_null($playerEntity->getPosition()) ? $playerEntity->getPosition()->getKey() : null,
            $playerEntity->getGender()
        );
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getId(): string
    {
        return $this->id;
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