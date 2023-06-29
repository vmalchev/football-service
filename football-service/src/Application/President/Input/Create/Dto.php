<?php

namespace Sportal\FootballApi\Application\President\Input\Create;


use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition(definition="v2_PresidentInput")
 */
class Dto implements IDto
{
    /**
     * @var string|null
     * @SWG\Property(property="name")
     */
    private ?string $name;

    /**
     * PresidentDto constructor.
     * @param string|null $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new NotBlank(),
            new Length(['max' => 80]),
        ]);
    }
}