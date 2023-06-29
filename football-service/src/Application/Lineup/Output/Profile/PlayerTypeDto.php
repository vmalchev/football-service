<?php


namespace Sportal\FootballApi\Application\Lineup\Output\Profile;


use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\ITranslatable;
use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;

class PlayerTypeDto implements IDto, JsonSerializable, ITranslatable
{
    private string $id;
    private string $name;
    private string $category;
    private string $code;

    /**
     * PlayerTypeDto constructor.
     * @param string $id
     * @param string $name
     * @param string $category
     * @param string $code
     */
    public function __construct(string $id, string $name, string $category, string $code)
    {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
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
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function getTranslationEntityType(): TranslationEntity
    {
        return TranslationEntity::LINEUP_PLAYER_TYPE();
    }

    public function setTranslation(ITranslationContent $translationContent): void
    {
        $this->name = $translationContent->getName() ?? $this->name;
    }
}