<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Venue;

use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchVenue")
 */
class Dto implements \JsonSerializable, IAssetable, ITranslatable
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

    private $assets;

    /**
     * Dto constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
     * @inheritDoc
     */
    public function jsonSerialize(): array
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