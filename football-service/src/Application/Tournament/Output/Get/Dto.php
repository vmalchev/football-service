<?php


namespace Sportal\FootballApi\Application\Tournament\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;


/**
 * @SWG\Definition(definition="v2_Tournament")
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
     * @var string|null
     * @SWG\Property(property="gender", enum=TOURNAMENT_GENDER)
     */
    private ?string $gender;

    /**
     * @var string|null
     * @SWG\Property(property="type", enum=TOURNAMENT_TYPE)
     */
    private ?string $type;

    /**
     * @var string|null
     * @SWG\Property(property="region", enum=TOURNAMENT_REGION)
     */
    private ?string $region;

    private $assets;

    /**
     * @param string $id
     * @param string $name
     * @param Country\Output\Get\Dto $country
     * @param string|null $gender
     * @param string|null $region
     * @param string|null $type
     */
    public function __construct(
        string $id,
        string $name,
        Country\Output\Get\Dto $country,
        ?string $gender,
        ?string $region,
        ?string $type
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
        $this->gender = $gender;
        $this->type = $type;
        $this->region = $region;
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
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::TOURNAMENT();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::TOURNAMENT();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}