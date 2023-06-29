<?php


namespace Sportal\FootballApi\Application\Venue\Dto;

use JsonSerializable;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class VenueProfileDto implements JsonSerializable
{
    /**
     * @SWG\Property()
     * @var float|null
     */
    private $lat;

    /**
     * @SWG\Property()
     * @var float|null
     */
    private $lng;

    /**
     * @SWG\Property()
     * @var int|null
     */
    private $capacity;

    /**
     * VenueProfileDto constructor.
     * @param string|null $lat
     * @param string|null $lng
     * @param string|null $capacity
     */
    public function __construct($lat = null, $lng = null, $capacity = null)
    {
        $this->lat = $lat;
        $this->lng = $lng;
        $this->capacity = $capacity;
    }

    /**
     * @return string|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @return string|null
     */
    public function getLng(): ?float
    {
        return $this->lng;
    }

    /**
     * @return string|null
     */
    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('lat', new Type(['type' => 'numeric']));
        $metadata->addPropertyConstraint('lng', new Type(['type' => 'numeric']));
        $metadata->addPropertyConstraint('capacity', new Positive());
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function create(?array $profile)
    {
        if (!is_null($profile)) {
            $profileDto = new VenueProfileDto();
            foreach ($profile as $property => $value) {
                if (property_exists($profileDto, $property)) {
                    $profileDto->{$property} = $value;
                }
            }
            return $profileDto;
        } else {
            return null;
        }
    }
}