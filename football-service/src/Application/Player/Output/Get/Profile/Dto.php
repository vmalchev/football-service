<?php


namespace Sportal\FootballApi\Application\Player\Output\Get\Profile;


use Sportal\FootballApi\Application\IAssetable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Application\Player\Dto\PlayerEditSocialDto;
use Sportal\FootballApi\Application\Player\Dto\PlayerProfileDto;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_PlayerProfile")
 */
class Dto implements \JsonSerializable, IAssetable, ITranslatable
{

    /**
     * @var string|null
     * @SWG\Property(property="id")
     */
    private ?string $id;

    /**
     * @var string
     * @SWG\Property(property="name")
     */
    private string $name;

    /**
     * @var \Sportal\FootballApi\Application\Country\Output\Get\Dto
     * @SWG\Property(property="country")
     */
    private \Sportal\FootballApi\Application\Country\Output\Get\Dto $country;

    /**
     * @var bool
     * @SWG\Property(property="active")
     */
    private ?bool $active;

    /**
     * @var string|null
     * @SWG\Property(property="birthdate")
     */
    private ?string $birthdate;

    /**
     * @var \Sportal\FootballApi\Application\City\Output\Get\Dto|null
     * @SWG\Property(property="birth_city")
     */
    private ?\Sportal\FootballApi\Application\City\Output\Get\Dto $birth_city;

    /**
     * @var PlayerProfileDto|null
     * @SWG\Property(property="profile")
     */
    private ?PlayerProfileDto $profile;

    /**
     * @var PlayerEditSocialDto|null
     * @SWG\Property(property="social")
     */
    private ?PlayerEditSocialDto $social;

    /**
     * @var string|null
     * @SWG\Property(property="position")
     */
    private ?string $position;

    /**
     * @var \Sportal\FootballApi\Application\Player\Output\Get\Teams\Dto[]
     * @SWG\Property(property="teams")
     */
    private array $teams;

    /**
     * @var string|null
     * @SWG\Property(property="gender", enum=PERSON_GENDER)
     */
    private ?string $gender;

    private $assets;

    /**
     * Dto constructor.
     * @param \Sportal\FootballApi\Application\Player\Output\Get\Dto $playerDto
     * @param \Sportal\FootballApi\Application\Player\Output\Get\Teams\Dto[] $teams
     */
    public function __construct(\Sportal\FootballApi\Application\Player\Output\Get\Dto $playerDto, array $teams)
    {
        $this->id = $playerDto->getId();
        $this->name = $playerDto->getName();
        $this->country = $playerDto->getCountry();
        $this->active = $playerDto->isActive();
        $this->birthdate = $playerDto->getBirthdate();
        $this->birth_city = $playerDto->getBirthCity();
        $this->position = $playerDto->getPosition();
        $this->social = $playerDto->getSocial();
        $this->profile = $playerDto->getProfile();
        $this->gender = $playerDto->getGender();

        $this->teams = $teams;
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
     * @return \Sportal\FootballApi\Application\Country\Output\Get\Dto
     */
    public function getCountry():\Sportal\FootballApi\Application\Country\Output\Get\Dto
    {
        return $this->country;
    }

    /**
     * @return bool
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @return string|null
     */
    public function getBirthdate(): ?string
    {
        return $this->birthdate;
    }

    /**
     * @return \Sportal\FootballApi\Application\City\Output\Get\Dto|null
     */
    public function getBirthCity()
    {
        return $this->birth_city;
    }

    /**
     * @return PlayerProfileDto|null
     */
    public function getProfile(): ?PlayerProfileDto
    {
        return $this->profile;
    }

    /**
     * @return PlayerEditSocialDto|null
     */
    public function getSocial(): ?PlayerEditSocialDto
    {
        return $this->social;
    }

    /**
     * @return string|null
     */
    public function getPosition(): ?string
    {
        return $this->position;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @return \Sportal\FootballApi\Application\Player\Output\Get\Teams\Dto[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getEntityType(): AssetEntityType
    {
        return AssetEntityType::PLAYER();
    }

    public function setAssets(AssetType $assetType, array $assetUrl): void
    {
        $this->assets[$assetType->getValue()] = $assetUrl;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::PLAYER();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}