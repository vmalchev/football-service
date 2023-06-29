<?php


namespace Sportal\FootballApi\Application\Player\Dto;

use JsonSerializable;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class PlayerProfileDto implements JsonSerializable
{
    /**
     * @var string|null
     * @SWG\Property()
     */
    private $height;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $weight;

    public function __construct(?string $height = null, ?string $weight = null)
    {
        $this->height = $height;
        $this->weight = $weight;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('height', new Type('digit'));
        $metadata->addPropertyConstraint('weight', new Type('digit'));
    }

    /**
     * @return string|null
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @return string|null
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), fn($property) => !is_null($property));
    }
}