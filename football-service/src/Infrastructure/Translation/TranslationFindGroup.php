<?php


namespace Sportal\FootballApi\Infrastructure\Translation;


use Sportal\FootballApi\Domain\Translation\ITranslationKey;

class TranslationFindGroup
{
    private string $entity;

    private string $language;

    private array $entityIds;

    /**
     * TranslationFindGroup constructor.
     * @param ITranslationKey $key
     */
    public function __construct(ITranslationKey $key)
    {
        $this->entity = $key->getEntity()->getValue();
        $this->language = $key->getLanguage();
        $this->entityIds = [$key->getEntityId()];
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
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return array
     */
    public function getEntityIds(): array
    {
        return $this->entityIds;
    }

    /**
     * @param string $entityId
     */
    public function addEntityId(string $entityId): void
    {
        $this->entityIds[] = $entityId;
    }

    public static function getId(ITranslationKey $key): string
    {
        return "{$key->getEntity()->getValue()}-{$key->getLanguage()}";
    }


}