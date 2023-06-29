<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Referee;


use JsonSerializable;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchReferee")
 */
class Dto implements JsonSerializable, IAssetable, ITranslatable
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
     * @SWG\Property(property="role", enum=MATCH_REFEREE_ROLE)
     * @var string
     */
    private string $role;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=PERSON_GENDER)
     */
    private ?string $gender;

    private $assets;

    /**
     * Dto constructor.
     * @param string $id
     * @param string $name
     * @param string $role
     * @param string|null $gender
     */
    public function __construct(string $id, string $name, string $role, ?string $gender)
    {
        $this->id = $id;
        $this->name = $name;
        $this->role = $role;
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
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
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