<?php


namespace Sportal\FootballApi\Application\MatchStatus\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchStatus")
 */
class Dto implements JsonSerializable, ITranslatable
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
     * @SWG\Property(property="short_name")
     * @var string|null
     */
    private ?string $short_name;

    /**
     * @SWG\Property(property="type", enum=MATCH_STATUS_TYPE)
     * @var string
     */
    private string $type;

    /**
     * @SWG\Property(property="code")
     * @var string
     */
    private string $code;

    /**
     * Dto constructor.
     * @param string $id
     * @param string $name
     * @param string|null $short_name
     * @param string $type
     * @param string $code
     */
    public function __construct(string $id, string $name, ?string $short_name, string $type, string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->short_name = $short_name;
        $this->type = $type;
        $this->code = $code;
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
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->short_name;
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
    public function getCode(): string
    {
        return $this->code;
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
        return TranslationEntity::MATCH_STATUS();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
        $this->short_name = $translationContent->getShortName() ?? $this->short_name;
    }
}