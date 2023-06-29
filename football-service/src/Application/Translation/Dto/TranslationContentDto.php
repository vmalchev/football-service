<?php

namespace Sportal\FootballApi\Application\Translation\Dto;


use JsonSerializable;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class TranslationContentDto implements JsonSerializable
{
    /**
     * @var string|null
     * @SWG\Property(property="name")
     */
    private ?string $name;

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
     * TranslationContentDto constructor.
     * @param string|null $name
     * @param string|null $three_letter_code
     * @param string|null $short_name
     */
    public function __construct(?string $name = null, ?string $three_letter_code = null, ?string $short_name = null)
    {
        $this->name = $name;
        $this->three_letter_code = $three_letter_code;
        $this->short_name = $short_name;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('three_letter_code', new Length(3));
        $metadata->addPropertyConstraint('short_name', new Length(null, 2, 20));
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->three_letter_code;
    }

    public function getShortName(): ?string
    {
        return $this->short_name;
    }
}