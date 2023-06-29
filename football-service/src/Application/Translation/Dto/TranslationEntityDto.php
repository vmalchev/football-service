<?php

namespace Sportal\FootballApi\Application\Translation\Dto;

use JsonSerializable;

/**
 * @SWG\Definition()
 */
class TranslationEntityDto implements JsonSerializable
{

    const FIELD_ENTITY = "entity";

    /**
     * @SWG\Property(enum=TRANSLATION_ENTITY)
     * @var string
     */
    private $entity;

    /**
     * TranslationEntityDto constructor.
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * @param string $entity
     * @return TranslationEntityDto
     */
    public static function create($entity): TranslationEntityDto
    {
        return new TranslationEntityDto($entity);
    }


}
