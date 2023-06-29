<?php


namespace Sportal\FootballApi\Application\Coach\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Coach")
 */
class Dto implements IDto, JsonSerializable, IAssetable, ITranslatable
{
    /**
     * @var string|null
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
     * @var string|null
     * @SWG\Property(property="birthdate")
     */
    private ?string $birthdate;

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
     * @param string|null $birthdate
     * @param string|null $gender
     */
    public function __construct(
        string $id,
        string $name,
        Country\Output\Get\Dto $country,
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
     * @return string
     */
    public function getBirthdate(): ?string
    {
        return $this->birthdate;
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