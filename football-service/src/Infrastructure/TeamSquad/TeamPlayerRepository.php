<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerRepository;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamPlayerEntity;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;

class TeamPlayerRepository implements ITeamPlayerRepository
{

    const TABLE_NAME = "team_player";

    /**
     * @var Database
     */
    private Database $db;

    /**
     * @var DatabaseUpdate
     */
    private DatabaseUpdate $dbUpdate;

    /**
     * @var TeamPlayerTableMapper
     */
    private TeamPlayerTableMapper $teamPlayerTableMapper;

    /**
     * TeamPlayerRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param TeamPlayerTableMapper $teamTableMapper
     */
    public function __construct(Database $db,
                                DatabaseUpdate $dbUpdate,
                                TeamPlayerTableMapper $teamTableMapper)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->teamPlayerTableMapper = $teamTableMapper;
    }

    /**
     * @param string $playerId
     * @param TeamSquadStatus|null $squadStatus
     * @return ITeamPlayerEntity[]
     */
    public function findByPlayer(string $playerId, ?TeamSquadStatus $squadStatus = null): array
    {
        $whereExpr = $this->db->andExpression()->eq(TeamPlayerTableMapper::FIELD_PLAYER_ID, $playerId);
        if ($squadStatus !== null) {
            $whereExpr->eq(TeamPlayerTableMapper::FIELD_ACTIVE, StatusDatabaseConverter::toValue($squadStatus));
        }

        $query = $this->db->createQuery(self::TABLE_NAME)
            ->where($whereExpr)
            ->addRelations($this->teamPlayerTableMapper->getRelations());

        return $this->db->getQueryResults($query, [$this->teamPlayerTableMapper, 'create']);
    }

    /**
     * @inheritDoc
     */
    public function findByTeam(ITeamEntity $teamEntity, ?TeamSquadStatus $squadStatus = null): array
    {
        $whereExpr = $this->db->andExpression()
            ->eq(TeamPlayerTableMapper::FIELD_TEAM_ID, $teamEntity->getId());
        if ($squadStatus !== null) {
            $whereExpr->eq(TeamPlayerTableMapper::FIELD_ACTIVE, StatusDatabaseConverter::toValue($squadStatus));
        }

        $query = $this->db->createQuery($this->teamPlayerTableMapper->getTableName())
            ->where($whereExpr)
            ->addJoin($this->teamPlayerTableMapper->joinPlayer())
            ->addOrderBy(TeamPlayerTableMapper::FIELD_ACTIVE, SortDirection::DESC)
            ->addOrderBy(TeamPlayerTableMapper::FIELD_SHIRT_NUMBER, SortDirection::ASC);

        return $this->db->getQueryResults($query, [$this->teamPlayerTableMapper, 'create']);
    }

    /**
     * @param ITeamPlayerEntity[] $teamPlayerEntities
     * @return IPlayerEntity[]
     */
    public function upsert(array $teamPlayerEntities): array
    {
        foreach ($teamPlayerEntities as $club) {
            if (!is_null($club->getId())) {
                $this->dbUpdate->update(self::TABLE_NAME, $club);
            } else {
                $this->dbUpdate->insertGeneratedId(self::TABLE_NAME, $club);
            }
        }

        return $teamPlayerEntities;
    }

    public function deleteByTeam(string $teamId): void
    {
        $this->dbUpdate->delete(TeamPlayerTableMapper::TABLE_NAME, [TeamPlayerTableMapper::FIELD_TEAM_ID => $teamId]);
    }

}