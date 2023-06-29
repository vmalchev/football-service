<?php


namespace Sportal\FootballApi\Application\Venue\Input\Create;


use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Venue\Dto\VenueProfileDto;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use App\Validation\Identifier;

/**
 * @SWG\Definition(definition="v2_post_venues", required={"name", "country_id"})
 */ 
class Dto implements IDto
{

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private $name;

    /**
     * @SWG\Property(property="country_id")
     * @var string
     */
    private $country_id;

    /**
     * @SWG\Property(property="city_id")
     * @var string|null
     */
    private $city_id;

    /**
     * @SWG\Property(property="profile")
     * @var VenueProfileDto|null
     */
    private $profile;

    /**
     * @param string $name
     * @param string $country_id
     * @param string|null $city_id
     * @param VenueProfileDto $profile
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
     * @return VenueProfileDto|null
     */
    public function getProfile(): ?VenueProfileDto
    {
        return $this->profile;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}