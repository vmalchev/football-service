<?php


namespace Sportal\FootballApi\Application\Country\Dto;

use JsonSerializable;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class CountryDto implements JsonSerializable, IAssetable, ITranslatable
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
     * @var string|null
     * @SWG\Property()
     */
    private $code;

    private $assets;

    /**
     * CountryDto constructor.
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

    public static function fromPlayerEntity(IPlayerEntity $playerEntity): CountryDto
    {
        return new CountryDto(
            $playerEntity->getCountry()->getId(),
            $playerEntity->getCountry()->getName(),
            $playerEntity->getCountry()->getCode(),
        );
    }

    public static function fromTeamEntity(ITeamEntity $teamEntity): CountryDto
    {
        return new CountryDto(
            $teamEntity->getCountry()->getId(),
            $teamEntity->getCountry()->getName(),
            $teamEntity->getCountry()->getCode(),
        );
    }

    public static function fromCountryEntity(ICountryEntity $countryEntity): CountryDto
    {
        return new CountryDto(
            $countryEntity->getId(),
            $countryEntity->getName(),
            $countryEntity->getCode(),
        );
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