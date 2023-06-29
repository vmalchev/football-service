<?php


namespace Sportal\FootballApi\Application\Team\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\Team\Dto\TeamSocialDto;
use Sportal\FootballApi\Application\Venue;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Team", required={"id", "name", "country", "type"})
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
     * @SWG\Property(property="short_name")
     */
    private ?string $short_name;

    /**
     * @var string
     * @SWG\Property(enum=TEAM_TYPE, property="type")
     */
    private string $type;

    /**
     * @var Country\Output\Get\Dto
     * @SWG\Property(property="country")
     */
    private Country\Output\Get\Dto $country;

    /**
     * @var Venue\Output\Get\Dto|null
     * @SWG\Property(property="venue")
     */
    private ?Venue\Output\Get\Dto $venue;

    /**
     * @var TeamSocialDto|null
     * @SWG\Property(property="social")
     */
    private ?TeamSocialDto $social;

    /**
     * @var int|null
     * @SWG\Property(property="founded")
     */
    private ?int $founded;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=TEAM_GENDER)
     */
    private ?string $gender;

    private $assets;

    /**
     * Dto constructor.
     * @param string $id
     * @param string $name
     * @param string|null $three_letter_code
     * @param string|null $short_name
     * @param string $type
     * @param Country\Output\Get\Dto $country
     * @param Venue\Output\Get\Dto|null $venue
     * @param TeamSocialDto|null $social
     * @param int|null $founded
     * @param string|null $gender
     */
    public function __construct(
        string $id,
        string $name,
        ?string $three_letter_code,
        ?string $short_name,
        string $type,
        Country\Output\Get\Dto $country,
        ?Venue\Output\Get\Dto $venue,
        ?TeamSocialDto $social,
        ?int $founded,
        ?string $gender
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->three_letter_code = $three_letter_code;
        $this->short_name = $short_name;
        $this->type = $type;
        $this->country = $country;
        $this->venue = $venue;
        $this->social = $social;
        $this->founded = $founded;
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
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->three_letter_code;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->short_name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Country\Output\Get\Dto
     */
    public function getCountry(): Country\Output\Get\Dto
    {
        return $this->country;
    }

    /**
     * @return Venue\Output\Get\Dto|null
     */
    public function getVenue(): ?Venue\Output\Get\Dto
    {
        return $this->venue;
    }

    /**
     * @return TeamSocialDto|null
     */
    public function getSocial(): ?TeamSocialDto
    {
        return $this->social;
    }

    /**
     * @return int|null
     */
    public function getFounded(): ?int
    {
        return $this->founded;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }


    /**
     * @return array|mixed
     */
    public function jsonSerialize()
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