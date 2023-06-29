<?php


namespace Sportal\FootballApi\Application\Match\Output\Get\Team;


use JsonSerializable;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchTeamDto")
 */
class Dto implements JsonSerializable, IAssetable, ITranslatable
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
     * @SWG\Property(property="three_letter_code")
     */
    private ?string $three_letter_code;

    /**
     * @var string|null
     * @SWG\Property(property="gender")
     */
    private ?string $gender;

    /**
     * @var string|null
     * @SWG\Property(property="short_name")
     */
    private ?string $short_name;

    /**
     * @var string
     * @SWG\Property(enum=TEAM_TYPE, property="type")
     */
    private string $type;

    /**
     * @var string|null
     * @SWG\Property(property="$shirt_color")
     */
    private ?string $shirt_color;

    private $assets;

    /**
     * TeamDto constructor.
     * @param string $id
     * @param string $name
     * @param string|null $three_letter_code
     * @param string|null $gender
     * @param string $type
     * @param string|null $shirt_color
     */
    public function __construct(string  $id, string $name, ?string $three_letter_code, ?string $gender,
                                ?string $short_name, string $type, ?string $shirt_color)
    {
        $this->id = $id;
        $this->name = $name;
        $this->three_letter_code = $three_letter_code;
        $this->gender = $gender;
        $this->short_name = $short_name;
        $this->type = $type;
        $this->shirt_color = $shirt_color;
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getShirtColor(): string
    {
        return $this->shirt_color;
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
        return AssetEntityType::TEAM();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    /**
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->three_letter_code;
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
    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::TEAM();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
        $this->three_letter_code = $translationContent->getThreeLetterCode() ?? $this->three_letter_code;
        $this->short_name = $translationContent->getShortName() ?? $this->short_name;
    }
}