<?php

namespace Sportal\FootballApi\Application\Translation\Dto;

use JsonSerializable;
use Sportal\FootballApi\Domain\Translation\TranslationEntity;
use Sportal\FootballApi\Infrastructure\Translation\Translation;
use Sportal\FootballApi\Infrastructure\Translation\TranslationContent;
use Sportal\FootballApi\Infrastructure\Translation\TranslationKey;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @SWG\Definition()
 */
class TranslationDto implements JsonSerializable
{
    /**
     * @var TranslationKeyDto|null
     * @SWG\Property()
     */
    private $key;

    /**
     * @var TranslationContentDto|null
     * @SWG\Property()
     */
    private $content;


    public function __construct(TranslationKeyDto $key = null, TranslationContentDto $content = null)
    {
        $this->key = $key;
        $this->content = $content;
    }

    /**
     * @param TranslationDto $dto
     * @return Translation
     */
    public static function toTranslation(TranslationDto $dto): Translation
    {
        return new Translation(
            new TranslationKey(
                TranslationEntity::{$dto->getTranslationKeyDto()->getEntity()}(),
                $dto->getTranslationKeyDto()->getEntityId(),
                $dto->getTranslationKeyDto()->getLanguage()
            ),
            new TranslationContent(
                $dto->getContent()->getName(),
                $dto->getContent()->getThreeLetterCode(),
                $dto->getContent()->getShortName()
            )
        );
    }

    /**
     * @return TranslationKeyDto
     */
    public function getTranslationKeyDto(): TranslationKeyDto
    {
        return $this->key;
    }

    /**
     * @return TranslationContentDto
     */
    public function getContent(): TranslationContentDto
    {
        return $this->content;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('key', new NotBlank());
        $metadata->addPropertyConstraint('content', new NotBlank());

        $metadata->addPropertyConstraint('key', new Valid());
        $metadata->addPropertyConstraint('content', new Valid());
    }

    /**
     * @param Translation $entity
     * @return TranslationDto
     */
    public static function fromTranslationEntity(Translation $entity): TranslationDto
    {
        return new TranslationDto(
            new TranslationKeyDto(
                $entity->getTranslationKey()->getEntity()->getKey(),
                $entity->getTranslationKey()->getEntityId(),
                $entity->getTranslationKey()->getLanguage()
            ),
            new TranslationContentDto(
                $entity->getContent()->getName(),
                $entity->getContent()->getThreeLetterCode(),
                $entity->getContent()->getShortName()
            )
        );
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
