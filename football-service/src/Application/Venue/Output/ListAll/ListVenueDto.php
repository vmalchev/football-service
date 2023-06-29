<?php


namespace Sportal\FootballApi\Application\Venue\Output\ListAll;

use JsonSerializable;
use Sportal\FootballApi\Application\City\Dto\CityDto;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class ListVenueDto implements JsonSerializable, ITranslatable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    private $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(property="country")
     * @var CountryDto|null
     */
    private $country;

    /**
     * @SWG\Property(property="city")
     * @var CityDto
     */
    private $city;

    /**
     * @SWG\Property(property="profile")
     * @var VenueProfileDto
     */
    private $profile;

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
        ?CityDto $city
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->city = $city;
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

    public function jsonSerialize()
    {
        return get_object_vars($this);
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