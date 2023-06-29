<?php


namespace Sportal\FootballApi\Application\Coach\Dto;

use App\Validation\Identifier;
use DateTime;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class CoachEditDto
{

    /**
     * @var string|null
     */
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
     * @var string|null
     * @SWG\Property(format="date")
     */
    private $birthdate;

    /**
     * CoachEditDto constructor.
     * @param string|null $id
     * @param string|null $name
     * @param string|null $country_id
     * @param string|null $birthdate
     */
    public function __construct(
        $id = null,
        $name = null,
        $country_id = null,
        $birthdate = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->country_id = $country_id;
        $this->birthdate = $birthdate;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new NotBlank(),
            new Length(['max' => 45]),
        ]);

        $metadata->addPropertyConstraints('country_id', [
            new NotBlank(),
            new Type(['type' => ['digit', 'numeric']]),
            new Identifier(),
        ]);

        $metadata->addPropertyConstraints('birthdate', [
            new NotBlank(['allowNull' => true]),
            new Date(),
        ]);
    }

    /**
     * @return int|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
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
     * @return int
     */
    public function getCountryId(): string
    {
        return $this->country_id;
    }

    /**
     * @return DateTime|null
     * @throws \Exception
     */
    public function getBirthdate(): ?DateTime
    {
        return !is_null($this->birthdate) ? new DateTime($this->birthdate) : null;
    }
}