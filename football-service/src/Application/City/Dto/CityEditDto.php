<?php


namespace Sportal\FootballApi\Application\City\Dto;

use App\Validation\Identifier;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class CityEditDto
{
    private $id;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $name;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $country_id;

    /**
     * CityEditDto constructor.
     * @param int|null $id
     * @param string|null $name
     * @param string|null $country_id
     */
    public function __construct($id = null, $name = null, $country_id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->country_id = $country_id;
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

        $metadata->addPropertyConstraints('country_id', [
            new NotBlank(),
            new Type(['type' => ['digit', 'numeric']]),
            new Identifier(),
        ]);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getCountryId(): ?string
    {
        return $this->country_id;
    }
}