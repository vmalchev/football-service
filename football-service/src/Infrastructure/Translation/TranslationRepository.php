<?php

namespace Sportal\FootballApi\Infrastructure\Translation;


use Sportal\FootballApi\Domain\Translation\ITranslationEntity;
use Sportal\FootballApi\Domain\Translation\ITranslationEntityKey;
use Sportal\FootballApi\Domain\Translation\ITranslationKey;
use Sportal\FootballApi\Domain\Translation\ITranslationRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class TranslationRepository implements ITranslationRepository
{
    /**
     * @var Database
     */
    private $db;

    /**
     * @var DatabaseUpdate
     */
    private $dbUpdate;

    /**
     * TranslationRepository constructor.
     *
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
    }

    /**
     * @param ITranslationEntity $translation
     * @return int
     * @throws \Exception
     */
    public function update(ITranslationEntity $translation): int
    {
        // TODO: Move updated_at to database layer.
        $translation->setUpdatedAt((new \DateTime()));
        return $this->dbUpdate->update(Translation::TABLE_NAME, $translation);
    }

    /**
     * @param ITranslationEntity $translation
     * @return int
     * @throws \Exception
     */
    public function create(ITranslationEntity $translation): int
    {
        // TODO: Move updated_at to database layer.
        $translation->setUpdatedAt((new \DateTime()));
        return $this->dbUpdate->insert(Translation::TABLE_NAME, $translation);
    }

    /**
     * @param ITranslationKey[] $keys
     * @return array
     */
    public function findByKeys(array $keys): array
    {
        if (!empty($keys)) {
            /** @var TranslationFindGroup[] $findGroups */
            $findGroups = [];
            foreach ($keys as $key) {
                $id = TranslationFindGroup::getId($key);
                if (!isset($findGroups[$id])) {
                    $findGroups[$id] = new TranslationFindGroup($key);
                } else {
                    $findGroups[$id]->addEntityId($key->getEntityId());
                }
            }
            $orExpr = $this->db->orExpression();
            foreach ($findGroups as $group) {
                $andExpr = $this->db->andExpression()
                    ->eq(Translation::FIELD_ENTITY, $group->getEntity())
                    ->in(Translation::FIELD_ENTITY_ID, $group->getEntityIds())
                    ->eq(Translation::FIELD_LANGUAGE_CODE, $group->getLanguage());
                $orExpr->add($andExpr);
            }
            $query = $this->db->createQuery(Translation::TABLE_NAME)->where($orExpr);
            return $this->db->getQueryResults($query, [Translation::class, 'create']);
        } else {
            return [];
        }
    }

    public function find(ITranslationKey $key): ?ITranslationEntity
    {
        $andExpr = $this->db->andExpression()
            ->eq(Translation::FIELD_ENTITY, $key->getEntity())
            ->eq(Translation::FIELD_ENTITY_ID, $key->getEntityId())
            ->eq(Translation::FIELD_LANGUAGE_CODE, $key->getLanguage());
        $query = $this->db->createQuery(Translation::TABLE_NAME)->where($andExpr);
        return $this->db->getSingleResult($query, [Translation::class, 'create']);
    }
}
