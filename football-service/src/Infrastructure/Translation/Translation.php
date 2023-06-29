<?php

namespace Sportal\FootballApi\Infrastructure\Translation;


use Sportal\FootballApi\Domain\Translation\ITranslationContent;
use Sportal\FootballApi\Domain\Translation\ITranslationEntity;
use Sportal\FootballApi\Domain\Translation\ITranslationKey;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class Translation implements ITranslationEntity, IDatabaseEntity
{
    const TABLE_NAME = "ml_content";
    const FIELD_ENTITY = "entity";
    const FIELD_ENTITY_ID = "entity_id";
    const FIELD_LANGUAGE_CODE = "language_code";
    const FIELD_CONTENT = "content";
    const FIELD_UPDATED_AT = "updated_at";

    /**
     * @var TranslationKey
     */
    private $translationKey;

    /**
     * @var TranslationContent
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct(ITranslationKey $translationKey, ITranslationContent $content, \DateTime $updatedAt = null)
    {
        $this->translationKey = $translationKey;
        $this->content = $content;
        $this->updatedAt = $updatedAt;
    }

    public static function create(array $data): Translation
    {
        $content = is_array($data[self::FIELD_CONTENT]) ? $data[self::FIELD_CONTENT] : json_decode($data[self::FIELD_CONTENT], true);
        return new Translation(
            new TranslationKey(
                new TranslationEntity($data[self::FIELD_ENTITY]),
                $data[self::FIELD_ENTITY_ID],
                $data[self::FIELD_LANGUAGE_CODE]
            ),
            TranslationContent::create($content)
        );
    }

    public function getTranslationKey(): ITranslationKey
    {
        return $this->translationKey;
    }

    /**
     * @return TranslationContent
     */
    public function getContent(): ITranslationContent
    {
        return $this->content;
    }

    public function getDatabaseEntry(): array
    {
        return [
            self::FIELD_ENTITY => $this->translationKey->getEntity()->getValue(),
            self::FIELD_ENTITY_ID => $this->translationKey->getEntityId(),
            self::FIELD_LANGUAGE_CODE => $this->translationKey->getLanguage(),
            self::FIELD_CONTENT => json_encode($this->content->toArray()),
            self::FIELD_UPDATED_AT => $this->getUpdatedAt(),
        ];
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt->format('Y-m-d H:i:s');
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getPrimaryKey(): array
    {
        return [
            self::FIELD_ENTITY => $this->translationKey->getEntity(),
            self::FIELD_ENTITY_ID => $this->translationKey->getEntityId(),
            self::FIELD_LANGUAGE_CODE => $this->translationKey->getLanguage(),
        ];
    }

}