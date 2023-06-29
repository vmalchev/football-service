<?php


namespace Sportal\FootballApi\Application\Venue\Output\Update;

use JsonSerializable;
use Sportal\FootballApi\Application\City;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\Venue\Dto\VenueProfileDto;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;

/**
 * @SWG\Definition(definition="v2_VenuesPut", required={"name", "country"})
 */
class Dto implements IDto, JsonSerializable, IAssetable, ITranslatable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    private string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * @SWG\Property(property="country")
     * @var Country\Output\Get\Dto
     */
    private ?Country\Output\Get\Dto $country;

    /**
     * @SWG\Property(property="city")
     * @var City\Output\Get\Dto|null
     */
    private ?City\Output\Get\Dto $city;

    /**
     * @SWG\Property(property="profile")
     * @var VenueProfileDto|null
     */
    private ?VenueProfileDto $profile;

    private $assets;

    /**
     * @param string $id
     * @param string $name
     * @param Country\Output\Get\Dto $country
     * @param City\Output\Get\Dto|null $city
     * @param VenueProfileDto|null $profile
     */
    public function __construct(
        string $id,
        string $name,
        Country\Output\Get\Dto $country,
        ?City\Output\Get\Dto $city,
        ?VenueProfileDto $profile
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->city = $city;
        $this->profile = $profile;
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
     * @return City\Output\Get\Dto|null
     */
    public function getCity(): ?City\Output\Get\Dto
    {
        return $this->city;
    }

    /**
     * @return VenueProfileDto|null
     */
    public function getProfile(): ?VenueProfileDto
    {
        return $this->profile;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::VENUE();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::VENUE();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}