<?php


namespace Sportal\FootballApi\Application\Translation\Dto;

use JsonSerializable;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Language;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class TranslationKeyDto implements JsonSerializable
{
    /**
     * @var string|null
     * @SWG\Property(enum=TRANSLATION_ENTITY)
     */
    private $entity;

    /**
     * @var string|null
     * @SWG\Property(property="entity_id")
     */
    private $entity_id;

    /**
     * @var string|null
     * @SWG\Property()
     */
    private $language;

    public function __construct(?string $entity = null, ?string $entity_id = null, ?string $language = null)
    {
        $this->entity = $entity;
        $this->entity_id = $entity_id;
        $this->language = $language;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('entity', new NotBlank());
        $metadata->addPropertyConstraint('entity', new Choice([
            'choices' => array_values(TranslationEntity::keys()),
            'message' => 'Choose a valid entity. Options are: ' . implode(", ", TranslationEntity::keys()),
        ]));

        $metadata->addPropertyConstraint('entity_id', new NotBlank());
        $metadata->addPropertyConstraint('entity_id', new Type([
            'type' => 'numeric',
            'message' => 'The value {{ value }} is not a valid {{ type }}.',
        ]));
        $metadata->addPropertyConstraint('entity_id', new LessThan([
            'value' => '2147483647',
        ]));

        $metadata->addPropertyConstraint('language', new NotBlank());
        // ISO 3166-1 alpha-2 - have exactly 2 characters.
        $metadata->addPropertyConstraint('language', new Language());
    }

    public static function toTranslationKey(TranslationKeyDto $translationKeyDto)
    {
        return new TranslationKey(
            !is_null($translationKeyDto->getEntity()) ? TranslationEntity::{$translationKeyDto->getEntity()}() : null,
            $translationKeyDto->getEntityId(),
            $translationKeyDto->getLanguage()
        );
    }

    /**
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return $this->entity;
    }

    /**
     * @return string|null
     */
    public function getEntityId(): ?string
    {
        return $this->entity_id;
    }

    /**
     * @return string|null
     */
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}