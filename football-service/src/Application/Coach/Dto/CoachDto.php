<?php


namespace Sportal\FootballApi\Application\Coach\Dto;

use JsonSerializable;
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
 */
class CoachDto implements JsonSerializable, IAssetable, ITranslatable, IEventNotificationable
{
    /**
     * @var int|null
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
     * @var string
     * @SWG\Property()
     */
    private $birthdate;

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
        ?string $birthdate,
        ?string $gender
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->birthdate = $birthdate;
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
        return AssetEntityType::COACH();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::COACH();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}