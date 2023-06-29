<?php


namespace Sportal\FootballApi\Application\Round\Output\Partial;

use JsonSerializable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchRound")
 */
class Dto implements JsonSerializable, ITranslatable
{

    /**
     * @var string
     */
    protected string $id;

    /**
     * @SWG\Property(property="key")
     * @var string
     */
    protected string $key;

    /**
     * @SWG\Property(property="name")
     * @var string
     */
    protected string $name;

    /**
     * @SWG\Property(property="type")
     * @var string|null
     */
    protected ?string $type;

    /**
     * RoundDto constructor.
     * @param string $id
     * @param string $key
     * @param string $name
     * @param string|null $type
     */
    public function __construct(string $id, string $key, string $name, ?string $type)
    {
        $this->id = $id;
        $this->key = $key;
        $this->type = $type;
        $this->name = $name;
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
    public function getKey(): string
    {
        return $this->key;
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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        $serialized =  get_object_vars($this);
        // id is not exposed to frontends since it has no use currently (the key is used for input/output)
        unset($serialized['id']);
        return $serialized;
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::ROUND_TYPE();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}