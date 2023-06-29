<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Standing\IStandingEntity;
use Sportal\FootballApi\Domain\Standing\IStandingRepository;
use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class StandingRepository implements IStandingRepository
{
    private Database $db;

    private DatabaseUpdate $dbUpdate;

    private StandingTableMapper $tableMapper;

    /**
     * StandingRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param StandingTableMapper $tableMapper
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate, StandingTableMapper $tableMapper)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->tableMapper = $tableMapper;
    }


    public function upsert(IStandingEntity $standingEntity): IStandingEntity
    {
        if (!$standingEntity instanceof StandingEntity) {
            throw new InvalidArgumentException("Entity with type:" . get_class($standingEntity) . " is not supported");
        }
        $existingEntity = $this->findExisting($standingEntity->getType(), $standingEntity->getEntityName(), $standingEntity->getEntityId());
        if ($existingEntity === null) {
            return $this->dbUpdate->insertGeneratedId(StandingTableMapper::TABLE_NAME, $standingEntity);
        } else {
            return $existingEntity;
        }
    }

    public function findExisting(StandingType $type, StandingEntityName $entity, string $entityId): ?IStandingEntity
    {
        $query = $this->db->createQuery(StandingTableMapper::TABLE_NAME)->where($this->db->andExpression()
            ->eq(StandingTableMapper::FIELD_ENTITY, $entity->getValue())
            ->eq(StandingTableMapper::FIELD_TYPE, $type->getValue())
            ->eq(StandingTableMapper::FIELD_ENTITY_ID, $entityId));
        return $this->db->getSingleResult($query, [$this->tableMapper, 'toEntity']);
    }
}