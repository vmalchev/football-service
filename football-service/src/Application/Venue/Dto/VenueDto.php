<?php


namespace Sportal\FootballApi\Application\Venue\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\City\Dto\CityDto;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IEventNotificationable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;

/**
 * @SWG\Definition()
 */
class VenueDto implements JsonSerializable, IAssetable, IEventNotificationable
{
    /**
     * @SWG\Property()
     * @var string
     */
    private $id;

    /**
     * @SWG\Property()
     * @var string
     */
    private $name;

    /**
     * @SWG\Property()
     * @var CountryDto|null
     */
    private $country;

    /**
     * @SWG\Property()
     * @var CityDto
     */
    private $city;

    /**
     * @SWG\Property()
     * @var VenueProfileDto
     */
    private $profile;

    private $assets;

    /**
     * VenueEditDto constructor.
     * @param string $id
     * @param string $name
     * @param CountryDto|null $country
     * @param CityDto|null $city
     * @param VenueProfileDto|null $profile
     */
    public function __construct(
        string $id,
        string $name,
        ?CountryDto $country,
        ?CityDto $city,
        ?VenueProfileDto $profile
    )
    {
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
     * @return CountryDto|null
     */
    public function getCountry(): ?CountryDto
    {
        return $this->country;
    }

    /**
     * @return CityDto|null
     */
    public function getCity(): ?CityDto
    {
        return $this->city;
    }

    /**
     * @return VenueProfileDto
     */
    public function getProfile(): VenueProfileDto
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
}