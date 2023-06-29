<?php


namespace Sportal\FootballApi\Application\Group\Input\Upsert;


use App\Validation\Identifier;
use App\Validation\SmallInt;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

/**
 * @SWG\Definition(definition="v2_StageGroup")
 */
class Dto implements IDto, ITranslatable, \JsonSerializable
{

    /**
     * @var string|null
     * @SWG\Property(property="id")
     */
    private ?string $id;

    /**
     * @var string
     * @SWG\Property(property="name")
     */
    private string $name;

    /**
     * @var int
     * @SWG\Property(property="order_in_stage")
     */
    private int $order_in_stage;

    public function __construct(?string $id,
                                string $name,
                                int $order_in_stage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->order_in_stage = $order_in_stage;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOrderInStage(): int
    {
        return $this->order_in_stage;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::GROUP();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public static function getValidatorConstraints(): Collection {
        return new Collection([
            'id' => [
                new NotBlank(['allowNull' => true]),
                new Type(['type' => ['digit', 'numeric']]),
                new Identifier()
            ],
            'name' => [
                new NotBlank(),
                new Type(['type' => 'string'])
            ],
            'order_in_stage' => [
                new NotBlank(),
                new Type(['type' => 'int']),
                new SmallInt()
            ]
        ]);
    }

}