<?php

namespace Sportal\FootballApi\Application\Language\Dto;

use JsonSerializable;

/**
 * @SWG\Definition()
 */
class LanguageDto implements JsonSerializable
{

    /**
     * @SWG\Property()
     * @var string
     */
    private $code;

    /**
     * LanguageDto constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
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

    /**
     * @param Language $entity
     * @return LanguageDto
     */
    public static function fromLanguageEntity($entity): LanguageDto
    {
        return new LanguageDto(
            $entity->getCode()
        );
    }


}
