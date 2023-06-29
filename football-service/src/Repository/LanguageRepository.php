<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Domain\ILanguageRepository;
use Sportal\FootballApi\Model\Language;

class LanguageRepository extends Repository implements ILanguageRepository
{

    public function createObject($languageArr)
    {
        $language = (new Language())->setDescription($languageArr['description'])
            ->setCode($languageArr['code']);
        return $language;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::find()
     * @return \Sportal\FootballApi\Model\Language
     */
    public function find($code)
    {
        return $this->getByPk(Language::class, [
            'code' => $code
        ], [
            $this,
            'createObject'
        ]);
    }

    /**
     *
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\Repository::findAll()
     * @return \Sportal\FootballApi\Model\Language
     */
    public function findAll($filter = array())
    {
        return $this->getAll(Language::class, $filter, [
            $this,
            'createObject'
        ]);
    }

    public function getLanguageCodes()
    {
        return $this->getAll(Language::class, [], function ($row) {
            return $row['code'];
        });
    }

    public function getModelClass()
    {
        return Language::class;
    }
}