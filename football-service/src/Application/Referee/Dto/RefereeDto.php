<?php


namespace Sportal\FootballApi\Application\Referee\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\IEventNotificationable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class RefereeDto implements JsonSerializable, IAssetable, ITranslatable, IEventNotificationable
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
     * @var CountryDto
     */
    private $country;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $birthdate;

    /**
     * @SWG\Property()
     * @var bool
     */
    private $active;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=PERSON_GENDER)
     */
    private ?string $gender;

    private $assets;

    /**
     * RefereeDto constructor.
     * @param string $id
     * @param string $name
     * @param CountryDto $country
     * @param string|null $birthdate
     * @param bool|null $active
     * @param string|null $gender
     */
    public function __construct(
        string $id,
        string $name,
        CountryDto $country,
        ?string $birthdate,
        ?bool $active,
        ?string $gender
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->birthdate = $birthdate;
        $this->active = $active;
        $this->gender = $gender;
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
        return AssetEntityType::REFEREE();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::REFEREE();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}