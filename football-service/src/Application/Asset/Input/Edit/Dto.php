<?php


namespace Sportal\FootballApi\Application\Asset\Input\Edit;


use App\Validation\Asset;
use App\Validation\Identifier;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Asset\AssetContextType;
use Sportal\FootballApi\Domain\Asset\AssetEntityType;
use Sportal\FootballApi\Domain\Asset\AssetType;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_AssetInput")
 */
class Dto implements \JsonSerializable, IDto
{
    /**
     * @var string
     * @SWG\Property(property="entity")
     */
    private string $entity;

    /**
     * @var string
     * @SWG\Property(property="entity_id")
     */
    private string $entity_id;

    /**
     * @var string
     * @SWG\Property(property="type")
     */
    private string $type;

    /**
     * @var string
     * @SWG\Property(property="path")
     */
    private string $path;

    /**
     * @var string|null
     * @SWG\Property(property="context_type")
     */
    private ?string $context_type;

    /**
     * @var string|null
     * @SWG\Property(property="context_id")
     */
    private ?string $context_id;

    /**
     * @param string $entity
     * @param string $entity_id
     * @param string $type
     * @param string $path
     * @param string|null $context_type
     * @param string|null $context_id
     */
    public function __construct(
        string $entity,
        string $entity_id,
        string $type,
        string $path,
        ?string $context_type = null,
        ?string $context_id = null
    ) {
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->type = $type;
        $this->path = $path;
        $this->context_type = $context_type;
        $this->context_id = $context_id;
    }


    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entity_id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string|null
     */
    public function getContextType(): ?string
    {
        return $this->context_type;
    }

    /**
     * @return string|null
     */
    public function getContextId(): ?string
    {
        return $this->context_id;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'type' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                    new Assert\Choice(
                        [
                            'choices' => AssetType::keys(),
                            'message' => 'Choose a valid type. Options are: ' . implode(", ", AssetType::keys()),
                        ]
                    ),
                    new Asset(),
                ],
                'entity' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                    new Assert\Choice(
                        [
                            'choices' => AssetEntityType::keys(),
                            'message' => 'Choose a valid entity type. Options are: ' . implode(
                                    ", ", AssetEntityType::keys()
                                ),
                        ]
                    ),
                ],
                'entity_id' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'path' => [
                    new Assert\Length(['max' => 255]),
                    new Assert\NotBlank(),
                ],
                'context_type' => new Assert\Optional(
                    [
                        new Assert\Length(['max' => 255]),
                        new Assert\NotBlank(['allowNull' => true]),
                        new Assert\Choice(
                            [
                                'choices' => AssetContextType::keys(),
                                'message' => 'Choose a valid entity type. Options are: ' . implode(
                                        ", ", AssetContextType::keys()
                                    ),
                            ]
                        )
                    ]
                ),
                'context_id' => new Assert\Optional(
                    [
                        new Assert\Length(['max' => 255]),
                        new Assert\NotBlank(['allowNull' => true]),
                        new Assert\Type(['type' => ['digit', 'numeric']]),
                        new Identifier(),
                    ]
                ),
            ]
        );
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}