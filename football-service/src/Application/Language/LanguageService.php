<?php

namespace Sportal\FootballApi\Application\Language;

use Sportal\FootballApi\Application\Language\Dto\LanguageDto;
use Sportal\FootballApi\Domain\Language\ILanguageRepository;

/**
 * LanguageService
 */
class LanguageService
{
    /**
     * @var ILanguageRepository
     */
    private $languageRepository;

    public function __construct(ILanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @return array
     */
    public function getAllCodes()
    {
        $result = $this->languageRepository->getAllLanguages();
        return array_map([LanguageDto::class, 'fromLanguageEntity'], $result);
    }
}