<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntityFactory;
use Sportal\FootballApi\Domain\Team\ITeamColorsRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class TeamColorsRepository implements ITeamColorsRepository
{

    private Database $db;
    private DatabaseUpdate $dbUpdate;

    /**
     * TeamColorsRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
    }

    public function insert(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntity
    {
        $this->dbUpdate->insert(TeamColorsTable::TABLE_NAME, $teamColorsEntity);
        return $teamColorsEntity;
    }

    public function update(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntity
    {
        $this->dbUpdate->update(TeamColorsTable::TABLE_NAME, $teamColorsEntity);
        return $teamColorsEntity;
    }

    public function upsert(ITeamColorsEntity $teamColorsEntity): ITeamColorsEntity
    {
        if (!$this->exists($teamColorsEntity->getEntityId(), $teamColorsEntity->getEntityType())) {
            return $this->insert($teamColorsEntity);
        } else {
            return $this->update($teamColorsEntity);
        }
    }

    public function exists(string $entityId, string $entityType): bool
    {
        return $this->db->exists(TeamColorsTable::TABLE_NAME,
            [
                TeamColorsTable::FIELD_ENTITY_ID => $entityId,
                TeamColorsTable::FIELD_ENTITY_TYPE => $entityType
            ]
        );
    }
}