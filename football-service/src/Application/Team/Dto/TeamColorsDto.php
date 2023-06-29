<?php


namespace Sportal\FootballApi\Application\Team\Dto;


use App\Validation\Identifier;
use Sportal\FootballApi\Domain\Match\ColorEntityType;
use Sportal\FootballApi\Domain\Match\ColorTeamType;
use Symfony\Component\Validator\Constraints as Assert;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition(definition="v2_TeamColors", required={"entity_type", "entity_id", "colors"})
 */
class TeamColorsDto implements \JsonSerializable
{

    /**
     * @var string
     * @SWG\Property(enum=COLOR_ENTITY_TYPE,property="entity_type")
     */
    private string $entity_type;

    /**
     * @var string
     * @SWG\Property(property="entity_id")
     */
    private string $entity_id;

    /**
     * @var array
     * @SWG\Property(
     *     property="colors",
     *     items=@SWG\Items(
     *       @SWG\Property(property="type", enum=COLOR_TEAM_TYPE, type="string"),
     *       @SWG\Property(property="color_code", type="string")
     *     )
     * )
     */
    private array $colors;

    /**
     * TeamColorsDto constructor.
     * @param string $entity_type
     * @param string $entity_id
     * @param array $colors
     */
    public function __construct(string $entity_type, string $entity_id, array $colors)
    {
        $this->entity_type = $entity_type;
        $this->entity_id = $entity_id;
        $this->colors = $colors;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entity_type;
    }

    /**
     * @param string $entity_type
     */
    public function setEntityType(string $entity_type): void
    {
        $this->entity_type = $entity_type;
    }

    /**
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entity_id;
    }

    /**
     * @param string $entity_id
     */
    public function setEntityId(string $entity_id): void
    {
        $this->entity_id = $entity_id;
    }

    /**
     * @return array
     */
    public function getColors(): array
    {
        return $this->colors;
    }

    /**
     * @param array $colors
     */
    public function setColors(array $colors): void
    {
        $this->colors = $colors;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'entity_type' => new Assert\Required([
                new Assert\Choice([
                    'choices' => ColorEntityType::toArray(),
                    'message' => 'Choose a valid entity type. Options are: ' . implode(", ", ColorEntityType::toArray())
                ])
            ]),
            'entity_id' => [
                new Identifier(),
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']])
            ],
            'colors' => [
                new Assert\Required([
                    new Assert\Type('array'),
                    new Assert\All([
                        new Assert\Collection([
                            "type" => [
                                new Assert\NotBlank(),
                                new Assert\Choice([
                                    'choices' => ColorTeamType::toArray(),
                                    'message' => 'Choose a team type. Options are: ' . implode(", ", ColorTeamType::toArray())
                                ])
                            ],
                            "color_code" => [
                                new Assert\NotBlank(),
                                new Assert\Regex("/^#[a-f0-9]{6}$/i")
                            ]
                        ])
                    ])
                ])
            ]
        ]);
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}