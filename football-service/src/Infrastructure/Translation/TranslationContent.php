<?php

namespace Sportal\FootballApi\Infrastructure\Translation;


use Sportal\FootballApi\Domain\Translation\ITranslationContent;

class TranslationContent implements ITranslationContent
{
    const FIELD_NAME = "name";
    const FIELD_THREE_LETTER_CODE = "three_letter_code";
    const FIELD_SHORT_NAME = "short_name";

    /**
     * @var string
     */
    private string $name;

    private ?string $threeLetterCode;

    private ?string $shortName;

    /**
     * TranslationContent constructor.
     * @param string $name
     * @param string|null $threeLetterCode
     * @param string|null $shortName
     */
    public function __construct(string $name, ?string $threeLetterCode = null, ?string $shortName = null)
    {
        $this->name = $name;
        $this->threeLetterCode = $threeLetterCode;
        $this->shortName = $shortName;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getThreeLetterCode(): ?string
    {
        return $this->threeLetterCode;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function toArray(): array
    {
        return [
            self::FIELD_NAME => $this->name,
            self::FIELD_THREE_LETTER_CODE => $this->threeLetterCode,
            self::FIELD_SHORT_NAME => $this->shortName
        ];
    }

    public static function create(array $data): TranslationContent
    {
        return new TranslationContent($data[self::FIELD_NAME],
            $data[self::FIELD_THREE_LETTER_CODE] ?? null,
            $data[self::FIELD_SHORT_NAME] ?? null);
    }

}