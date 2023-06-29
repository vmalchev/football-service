<?php


namespace Sportal\FootballApi\Application\City\Output\Get;

use JsonSerializable;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_City")
 */
class Dto implements IDto, JsonSerializable, ITranslatable
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
     * @var Country\Output\Get\Dto|null
     * @SWG\Property(property="country")
     */
    private ?Country\Output\Get\Dto $country;

    /**
     * @param string $id
     * @param string $name
     * @param Country\Output\Get\Dto|null $country
     */
    public function __construct(string $id, string $name, ?Country\Output\Get\Dto $country)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country = $country;
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
     * @return Country\Output\Get\Dto|null
     */
    public function getCountry(): ?Country\Output\Get\Dto
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