<?php


namespace Sportal\FootballApi\Application\Group\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Group")
 */
class Dto implements IDto, JsonSerializable, ITranslatable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    private string $id;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    private string $name;

    /**
     * @var int|null
     * @SWG\Property(property="order_in_stage")
     */
    private ?int $order_in_stage;

    /**
     * Dto constructor.
     * @param string $id
     * @param string $name
     * @param int|null $order_in_stage
     */
    public function __construct(string $id, string $name, ?int $order_in_stage)
    {
        $this->id = $id;
        $this->name = $name;
        $this->order_in_stage = $order_in_stage;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getOrderInStage(): ?int
    {
        return $this->order_in_stage;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::GROUP();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }

}