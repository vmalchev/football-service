<?php


namespace Sportal\FootballApi\Application\Team\Output\Profile;

use JsonSerializable;
use Sportal\FootballApi\Application\Coach;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IEventNotificationable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\President;
use Sportal\FootballApi\Application\Season;
use Sportal\FootballApi\Application\Team\Dto\TeamSocialDto;
use Sportal\FootballApi\Application\Venue;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_TeamProfile")
 */
class Dto implements IDto, JsonSerializable, IAssetable, ITranslatable, IEventNotificationable
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
     * @SWG\Property(property="type")
     */
    private string $type;

    /**
     * @var int|null
     * @SWG\Property(property="founded")
     */
    private ?int $founded;

    /**
     * @var Country\Output\Get\Dto|null
     * @SWG\Property(property="country")
     */
    private ?Country\Output\Get\Dto $country;

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
     * @var Coach\Output\Get\Dto|null
     * @SWG\Property(property="coach")
     */
    private ?Coach\Output\Get\Dto $coach;

    /**
     * @var President\Output\Profile\Dto|null
     * @SWG\Property(property="president")
     */
    private ?President\Output\Profile\Dto $president;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=TEAM_GENDER)
     */
    private ?string $gender;

    /**
     * @var Season\Output\Get\Dto[]
     * @SWG\Property(property="active_seasons")
     */
    private array $active_seasons;

    private $assets;

    /**
     * @var array|null
     * @SWG\Property(
     *     property="shirt_colors",
     *     items=@SWG\Items(
     *       @SWG\Property(property="type", enum=COLOR_TEAM_TYPE, type="string"),
     *       @SWG\Property(property="color_code", type="string")
     *     )
     * )
     */
    private ?array $shirt_colors;

    /**
     * @param string $id
     * @param string $name
     * @param string|null $three_letter_code
     * @param string|null $short_name
     * @param string $type
     * @param int|null $founded
     * @param Country\Output\Get\Dto|null $country
     * @param Venue\Output\Get\Dto|null $venue
     * @param TeamSocialDto|null $social
     * @param Coach\Output\Get\Dto|null $coach
     * @param President\Output\Profile\Dto|null $president
     * @param array|null $shirt_colors
     * @param string|null $gender
     * @param array $active_seasons
     */
    public function __construct(
        string                        $id,
        string                        $name,
        ?string                       $three_letter_code,
        ?string                       $short_name,
        string                        $type,
        ?int                          $founded,
        ?Country\Output\Get\Dto       $country,
        ?Venue\Output\Get\Dto         $venue,
        ?TeamSocialDto                $social,
        ?Coach\Output\Get\Dto         $coach,
        ?President\Output\Profile\Dto $president,
        ?array                        $shirt_colors,
        ?string                       $gender,
        array                         $active_seasons
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->three_letter_code = $three_letter_code;
        $this->short_name = $short_name;
        $this->type = $type;
        $this->founded = $founded;
        $this->country = $country;
        $this->venue = $venue;
        $this->social = $social;
        $this->coach = $coach;
        $this->president = $president;
        $this->shirt_colors = $shirt_colors;
        $this->gender = $gender;
        $this->active_seasons = $active_seasons;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::TEAM();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
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
     * @return int|null
     */
    public function getFounded(): ?int
    {
        return $this->founded;
    }

    /**
     * @return Country\Output\Get\Dto|null
     */
    public function getCountry(): ?Country\Output\Get\Dto
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
     * @return Coach\Output\Get\Dto|null
     */
    public function getCoach(): ?Coach\Output\Get\Dto
    {
        return $this->coach;
    }

    /**
     * @return President\Output\Profile\Dto|null
     */
    public function getPresident(): ?President\Output\Profile\Dto
    {
        return $this->president;
    }

    /**
     * @return array|null
     */
    public function getShirtColors(): ?array
    {
        return $this->shirt_colors;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return Season\Output\Get\Dto[]
     */
    public function getActiveSeasons(): array
    {
        return $this->active_seasons;
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