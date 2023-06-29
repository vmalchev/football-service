<?php


namespace Sportal\FootballApi\Application\Country\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Country")
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
     * @var string|null
     * @SWG\Property(property="code")
     */
    private ?string $code;

    private $assets;

    /**
     * @param string $id
     * @param string $name
     * @param string|null $code
     */
    public function __construct(string $id, string $name, ?string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->code = $code;
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
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::COUNTRY();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::COUNTRY();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}