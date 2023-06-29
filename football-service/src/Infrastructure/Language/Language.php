<?php

namespace Sportal\FootballApi\Infrastructure\Language;

use Sportal\FootballApi\Domain\Language\ILanguage;

/**
 * Language
 */
class Language implements ILanguage
{

    const FIELD_CODE = "code";
    const FIELD_DESCRIPTION = "description";

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $description;

    /**
     * Language constructor.
     * @param string $code
     * @param string $description
     */
    public function __construct(string $code, string $description)
    {
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public static function create(array $data): Language
    {
        return new Language(
            $data[self::FIELD_CODE],
            $data[self::FIELD_DESCRIPTION]
        );
    }
}
