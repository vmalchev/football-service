<?php


namespace Sportal\FootballApi\Application\Venue\Dto;

use App\Validation\Identifier;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition(required={"name", "country_id"})
 */
class VenueEditDto
{
    /**
     * @SWG\Property()
     * @var string|null
     */
    private $name;

    /**
     * @SWG\Property()
     * @var string|null
     */
    private $country_id;

    /**
     * @SWG\Property()
     * @var string|null
     */
    private $city_id;

    /**
     * @SWG\Property()
     * @var VenueProfileDto|null
     */
    private $profile;

    /**
     * VenueEditDto constructor.
     * @param string|null $name
     * @param string|null $country_id
     * @param string|null $city_id
     * @param VenueProfileDto|null $profile
     */
    public function __construct(
        $name = null,
        $country_id = null,
        $city_id = null,
        ?VenueProfileDto $profile = null
    )
    {
        $this->name = $name;
        $this->country_id = $country_id;
        $this->city_id = $city_id;
        $this->profile = $profile;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('name', [
            new NotBlank(),
            new Length(['max' => 60]),
        ]);

        $metadata->addPropertyConstraints('country_id', [
            new NotBlank(),
            new Type(['type' => ['digit', 'numeric']]),
            new Identifier(),
        ]);

        $metadata->addPropertyConstraints('city_id', [
            new NotBlank(['allowNull' => true]),
            new Type(['type' => ['digit', 'numeric']]),
            new Identifier(),
        ]);

        $metadata->addPropertyConstraint('profile', new Valid());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getCountryId(): string
    {
        return $this->country_id;
    }

    /**
     * @return string
     */
    public function getCityId(): ?string
    {
        return $this->city_id;
    }

    /**
     * @return VenueProfileDto
     */
    public function getProfile(): ?VenueProfileDto
    {
        return $this->profile;
    }

}