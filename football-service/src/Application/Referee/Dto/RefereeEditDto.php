<?php


namespace Sportal\FootballApi\Application\Referee\Dto;

use DateTimeInterface;
use App\Validation\Identifier;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;


/**
 * @SWG\Definition()
 */
class RefereeEditDto
{
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
     * @var DateTimeInterface|null
     * @SWG\Property()
     */
    private $birthdate;

    /**
     * @var bool|null
     * @SWG\Property()
     */
    private $active;

    /**
     * @param string|null $name
     * @param string|null $country_id
     * @param DateTimeInterface|null $birthdate
     * @param bool|null $active
     */
    public function __construct(
        $name = null,
        $country_id = null,
        $birthdate = null,
        $active = null
    ) {
        $this->name = $name;
        $this->country_id = $country_id;
        $this->birthdate = $birthdate;
        $this->active = $active;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints(
            'name', [
                      new NotBlank(),
                      new Length(["min" => 3, 'max' => 45])
                  ]
        );

        $metadata->addPropertyConstraints(
            'country_id', [
                            new NotBlank(),
                            new Type(['type' => ['digit', 'numeric']]),
                            new Identifier(),
                        ]
        );

        $metadata->addPropertyConstraints(
            'birthdate', [
                           new Date(),
                           new Length(['min' => 1])
                       ]
        );

        $metadata->addPropertyConstraint('active', new Type('bool'));
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

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthdate(): ?\DateTimeInterface
    {
        return !is_null($this->birthdate) ? \DateTime::createFromFormat('!Y-m-d', $this->birthdate) : null;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }
}