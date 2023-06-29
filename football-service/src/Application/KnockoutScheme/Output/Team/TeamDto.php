<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Team;

use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_KnockoutTeam")
 */
class TeamDto implements \JsonSerializable, ITranslatable, IAssetable
{

    /**
     * @SWG\Property(property="id")
     * @var string|null
     */
    private ?string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * @SWG\Property(property="three_letter_code")
     * @var string|null
     */
    private ?string $three_letter_code;

    /**
     * @SWG\Property(property="gender")
     * @var string|null
     */
    private ?string $gender;

    /**
     * @SWG\Property(property="type")
     * @var string
     */
    private string $type;

    private array $assets;

    /**
     * TeamDto constructor.
     * @param string|null $id
     * @param string $name
     * @param string|null $three_letter_code
     * @param string|null $gender
     * @param string $type
     */
    public function __construct(?string $id, string $name, ?string $three_letter_code, ?string $gender, string $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->three_letter_code = $three_letter_code;
        $this->gender = $gender;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getId(): ?string
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

    public function getThreeLetterCode(): ?string
    {
        return $this->three_letter_code;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::TEAM();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
        $this->three_letter_code = $translationContent->getThreeLetterCode() ?? $this->three_letter_code;
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::TEAM();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}