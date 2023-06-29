<?php


namespace Sportal\FootballApi\Infrastructure\Asset;


use Ramsey\Uuid\Uuid;
use Sportal\FootballApi\Domain\Asset\IAssetEntity;
use Sportal\FootballApi\Domain\Asset\IAssetEntityFactory;
use Sportal\FootballApi\Domain\Asset\IAssetRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class AssetRepository implements IAssetRepository
{
    /**
     * @var Database
     */
    private Database $db;

    /**
     * @var DatabaseUpdate
     */
    private DatabaseUpdate $dbUpdate;

    /**
     * @var IAssetEntityFactory
     */
    private IAssetEntityFactory $assetFactory;

    /**
     * AssetRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param IAssetEntityFactory $assetFactory
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate, IAssetEntityFactory $assetFactory)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->assetFactory = $assetFactory;
    }

    /**
     * @param IAssetEntity $assetEntity
     * @return IAssetEntity
     * @throws \Exception
     */
    public function update(IAssetEntity $assetEntity): IAssetEntity
    {
        $this->dbUpdate->update(AssetTable::TABLE_NAME, $assetEntity);

        return $assetEntity;
    }

    /**
     * @param IAssetEntity $assetEntity
     * @return IAssetEntity
     * @throws \Exception
     */
    public function create(IAssetEntity $assetEntity): IAssetEntity
    {
        $this->dbUpdate->insert(
            AssetTable::TABLE_NAME,
            $this->assetFactory
                ->setAssetEntity($assetEntity)
                ->setId(Uuid::uuid4()->toString())
                ->create()
        );

        return $assetEntity;
    }

    public function delete(IAssetEntity $assetEntity): void
    {
        $this->dbUpdate->delete(AssetTable::TABLE_NAME, [AssetTable::FIELD_ID => $assetEntity->getId()]);
    }


    /**
     * @param IAssetEntity $assetEntity
     * @return IAssetEntity|null
     */
    public function find(IAssetEntity $assetEntity): ?IAssetEntity
    {
        $andExpr = $this->db->andExpression()
            ->eq(AssetTable::FIELD_ENTITY, $assetEntity->getEntity())
            ->eq(AssetTable::FIELD_ENTITY_ID, $assetEntity->getEntityId())
            ->eq(AssetTable::FIELD_TYPE, $assetEntity->getType())
            ->eq(AssetTable::FIELD_CONTEXT_TYPE, $assetEntity->getContextType())
            ->eq(AssetTable::FIELD_CONTEXT_ID, $assetEntity->getContextId());

        $query = $this->db->createQuery(AssetTable::TABLE_NAME)->where($andExpr);

        return $this->db->getSingleResult($query, [$this->assetFactory, "createFromArray"]);
    }

    public function entityExists(string $table, array $primaryKey): bool
    {
        return $this->db->exists($table, $primaryKey);
    }

    /**
     * @param AssetEntityFilter[] $entities
     * @return IAssetEntity[]
     */
    public function findByEntities(array $entities): array
    {
        if (!empty($entities)) {
            $entityGroups = [];
            foreach ($entities as $entity) {
                $entityGroups[$entity->getEntity()->getValue()][] = $entity->getEntityId();
            }
            $orExpr = $this->db->orExpression();
            foreach ($entityGroups as $entity => $entityIds) {
                $andExpr = $this->db->andExpression()
                    ->eq(AssetTable::FIELD_ENTITY, $entity)
                    ->in(AssetTable::FIELD_ENTITY_ID, array_unique($entityIds));
                $orExpr->add($andExpr);
            }
            $query = $this->db->createQuery(AssetTable::TABLE_NAME)->where($orExpr);
            return $this->db->getQueryResults($query, [
                $this->assetFactory,
                "createFromArray"
            ]);
        } else {
            return [];
        }
    }

}