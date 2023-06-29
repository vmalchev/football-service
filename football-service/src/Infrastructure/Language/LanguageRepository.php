<?php

namespace Sportal\FootballApi\Infrastructure\Language;

use Sportal\FootballApi\Domain\Language\ILanguageRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;

/**
 * LanguageRepository
 */
class LanguageRepository implements ILanguageRepository
{
    const LANGUAGE_TABLE = 'language';
    /**
     * @var Database
     */
    private $db;

    /**
     * LanguageRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @return Language[]
     */
    public function getAllLanguages(): array
    {
        $query = $this->db->createQuery(self::LANGUAGE_TABLE);

        return $this->db->getQueryResults($query, [
            Language::class,
            "create"
        ]);
    }

    public function exists(string $languageCode): bool
    {
        return $this->db->exists(self::LANGUAGE_TABLE, ['code' => $languageCode]);
    }
}