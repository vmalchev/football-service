<?php


namespace Sportal\FootballApi\Application\City\Dto;


use JsonSerializable;
use Sportal\FootballApi\Application\Country\Dto\CountryDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Application\IEventNotificationable;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class CityDto implements JsonSerializable, ITranslatable, IEventNotificationable
{
    /**
     * @var string|null
     * @SWG\Property()
     */
    private $id;

    /**
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * @var CountryDto
     * @SWG\Property()
     */
    private $country;

    /**
     * CityDto constructor.
     * @param string|null $id
     * @param string|null $name
     * @param CountryDto|null $country
     */
    public function __construct(string $id, string $name, ?CountryDto $country = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new NotBlank(),
            new Length(['max' => 80]),
        ]);

        $metadata->addPropertyConstraints('country_id', [
            new NotBlank(),
            new Type('digit'),
        ]);
    }

    /**
     * @return string|null
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return CountryDto
     */
    public function getCountry(): CountryDto
    {
        return $this->country;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::CITY();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}