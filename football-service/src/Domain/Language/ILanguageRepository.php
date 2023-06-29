<?php

namespace Sportal\FootballApi\Domain\Language;

interface ILanguageRepository
{
    /**
     * @return ILanguage[]
     */
    public function getAllLanguages(): array;

    public function exists(string $languageCode): bool;
}