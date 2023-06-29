<?php


namespace Sportal\FootballApi\Infrastructure\Blacklist;

use Ramsey\Uuid\Uuid;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistEntity;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistKey;
use Sportal\FootballApi\Domain\Blacklist\IBlacklistRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class BlacklistRepository implements IBlacklistRepository
{
    /**
     * @var Database
     */
    private Database $db;

    private DatabaseUpdate $dbUpdate;

    /**
     * BlacklistRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
    }

    /**
     * @param IBlacklistKey[] $blacklistKeys
     * @return IBlacklistEntity[]
     */
    public function insertAll(array $blacklistKeys): array
    {
        return $this->db->transactional(function (DatabaseUpdate $update) use ($blacklistKeys) {
            $entities = [];
            foreach ($blacklistKeys as $blacklistKey) {
                $entity = new Blacklist(
                    Uuid::uuid4()->toString(),
                    $blacklistKey
                );
                $update->insert(Blacklist::TABLE_NAME, $entity);
                $entities[] = $entity;
            }
            return $entities;
        });
    }

    /**
     * @param IBlacklistKey[] $blacklistKeys
     */
    public function deleteAll(array $blacklistKeys): void
    {
        $this->db->transactional(function () use ($blacklistKeys) {
            $blacklists = $this->findByKeys($blacklistKeys);

            foreach ($blacklists as $blacklist) {
                if (!is_null($blacklist)) {
                    $this->delete($blacklist->getId());
                }
            }
        });
    }

    public function delete(string $id): bool
    {
        return $this->db->delete(Blacklist::TABLE_NAME, [Blacklist::FIELD_ID => $id]) === 1;
    }

    public function exists(IBlacklistKey $key): bool
    {
        return $this->db->exists(Blacklist::TABLE_NAME, [
            Blacklist::FIELD_TYPE => $key->getType()->getValue(),
            Blacklist::FIELD_ENTITY => $key->getEntity()->getValue(),
            Blacklist::FIELD_ENTITY_ID => $key->getEntityId(),
            Blacklist::FIELD_CONTEXT => $key->getContext()
        ]);
    }

    /**
     * @param IBlacklistKey[] $keys
     * @return IBlacklistEntity[]
     */
    public function findByKeys(array $keys): array
    {
        if (!empty($keys)) {
            $orExpr = $this->db->orExpression();
            foreach ($keys as $key) {
                $andExpr = $this->db->andExpression()
                    ->eq(Blacklist::FIELD_TYPE, $key->getType()->getValue())
                    ->eq(Blacklist::FIELD_ENTITY, $key->getEntity()->getValue())
                    ->eq(Blacklist::FIELD_ENTITY_ID, $key->getEntityId())
                    ->eq(Blacklist::FIELD_CONTEXT, $key->getContext());
                $orExpr->add($andExpr);
            }
            $query = $this->db->createQuery(Blacklist::TABLE_NAME)->where($orExpr);
            return $this->db->getQueryResults($query, [
                Blacklist::class,
                "create"
            ]);
        } else {
            return [];
        }
    }

    public function upsert(IBlacklistKey $blacklistKey): void
    {
        if (!$this->exists($blacklistKey)) {
            $entity = new Blacklist(
                Uuid::uuid4()->toString(),
                $blacklistKey
            );
            $this->dbUpdate->insert(Blacklist::TABLE_NAME, $entity);
        }
    }
}