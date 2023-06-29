<?php


namespace Sportal\FootballApi\Application\ProviderMappings\Dto\Input;


use App\Validation\Identifier;
use Sportal\FootballApi\Adapter\EntityType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Dto
 * @package Sportal\FootballApi\Application\ProviderMappings\Dto\Input
 *
 * @SWG\Definition(definition="v2_MappingRequestDto", required={"entity_type", "provider_id"})
 */
class MappingRequestDto implements \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(property="entity_type", enum=ENTITY_TYPE, type="string")
     */
    private string $entity_type;

    /**
     * @var string
     * @SWG\Property(property="provider_id")
     */
    private string $provider_id;

    /**
     * Dto constructor.
     * @param string $entity_type
     * @param string $provider_id
     */
    public function __construct(string $entity_type, string $provider_id)
    {
        $this->entity_type = $entity_type;
        $this->provider_id = $provider_id;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'entity_type' => [
                new Assert\Choice([
                    'choices' => EntityType::keys(),
                    'message' => 'Choose an entity type. Options are: ' . implode(", ", EntityType::keys())
                ])
            ],
            'provider_id' => [
                new Identifier(),
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']])
            ]
        ]);
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entity_type;
    }

    /**
     * @return string
     */
    public function getProviderId(): string
    {
        return $this->provider_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}