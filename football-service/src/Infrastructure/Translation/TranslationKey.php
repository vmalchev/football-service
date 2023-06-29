<?php

namespace Sportal\FootballApi\Infrastructure\Translation;

use Sportal\FootballApi\Domain\Translation\ITranslationKey;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;

class TranslationKey implements ITranslationKey
{
    /**
     * @var TranslationEntity
     */
    private $entity;

    /**
     * @var string
     */
    private $entity_id;

    /**
     * @var string
     */
    private $language;

    public function __construct(TranslationEntity $entity, string $entity_id, string $language)
    {
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->language = $language;
    }

    /**
     * @return TranslationEntity
     */
    public function getEntity(): TranslationEntity
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
    public function getLanguage(): string
    {
        return $this->language;
    }

    public function __toString(): string
    {
        return "{$this->entity->getValue()}-{$this->entity_id}-{$this->language}";
    }
}