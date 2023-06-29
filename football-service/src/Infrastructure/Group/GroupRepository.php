<?php


namespace Sportal\FootballApi\Infrastructure\Group;


use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\Group\IGroupRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;

class GroupRepository implements IGroupRepository
{
    private Database $db;

    private GroupTableMapper $tableMapper;

    private DatabaseUpdate $dbUpdate;

    /**
     * StageRepository constructor.
     * @param Database $db
     * @param GroupTableMapper $tableMapper
     * @param DatabaseUpdate $dbUpdate
     */
    public function __construct(Database $db, GroupTableMapper $tableMapper, DatabaseUpdate $dbUpdate)
    {
        $this->db = $db;
        $this->tableMapper = $tableMapper;
        $this->dbUpdate = $dbUpdate;
    }


    public function findById(string $id): ?IGroupEntity
    {
        $expr = $this->db->andExpression()->eq(GroupTableMapper::FIELD_ID, $id);
        $query = $this->db->createQuery(GroupTableMapper::TABLE_NAME)
            ->where($expr)
            ->addRelations($this->tableMapper->getRelations());
        return $this->db->getSingleResult($query, [$this->tableMapper, 'toEntity']);
    }

    public function findByStageId(string $stageId): array
    {
        $expr = $this->db->andExpression()->eq(GroupTableMapper::FIELD_STAGE_ID, $stageId);
        $query = $this->db->createQuery(GroupTableMapper::TABLE_NAME)
            ->where($expr)
            ->addRelations($this->tableMapper->getRelations())
            ->addOrderBy(GroupTableMapper::FIELD_SORT_ORDER, SortDirection::ASC);
        return $this->db->getQueryResults($query, [$this->tableMapper, 'toEntity']);
    }

    public function create(IGroupEntity $groupEntity): IGroupEntity
    {
        $insertedEntity = $this->dbUpdate->insertGeneratedId($this->tableMapper->getTableName(), $groupEntity);

        $groupCount = count($this->findByStageId($groupEntity->getStageId()));
        $this->dbUpdate->updateMany(
            StageTableMapper::TABLE_NAME,
            [StageTableMapper::FIELD_STAGE_GROUPS => $groupCount],
            [StageTableMapper::FIELD_ID => $groupEntity->getStageId()]
        );

        return $insertedEntity;
    }

    public function update(IGroupEntity $groupEntity): void
    {
        $this->dbUpdate->update($this->tableMapper->getTableName(), $groupEntity);
    }

    public function delete(string $id)
    {
        $this->dbUpdate->delete($this->tableMapper->getTableName(), [GroupTableMapper::FIELD_ID => $id]);
    }

    public function exists(string $id): bool
    {
        return $this->db->exists($this->tableMapper->getTableName(), [GroupTableMapper::FIELD_ID => $id]);
    }

}