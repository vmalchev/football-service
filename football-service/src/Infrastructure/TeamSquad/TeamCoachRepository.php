<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use InvalidArgumentException;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamCoachRepository;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;

class TeamCoachRepository implements ITeamCoachRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private TeamCoachTableMapper $mapper;

    /**
     * TeamCoachRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param TeamCoachTableMapper $mapper
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate, TeamCoachTableMapper $mapper)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->mapper = $mapper;
    }

    /**
     * @inheritDoc
     */
    public function findByTeam(ITeamEntity $teamEntity, ?TeamSquadStatus $squadStatus = null): array
    {
        $whereExpr = $this->db->andExpression()->eq(TeamCoachTableMapper::FIELD_TEAM_ID, $teamEntity->getId());
        if ($squadStatus !== null) {
            $whereExpr->eq(TeamCoachTableMapper::FIELD_ACTIVE, StatusDatabaseConverter::toValue($squadStatus));
        }

        $query = $this->db->createQuery($this->mapper->getTableName())
            ->where($whereExpr)
            ->addRelations($this->mapper->getRelations())
            ->addOrderBy(TeamCoachTableMapper::FIELD_ACTIVE, SortDirection::DESC);

        return $this->db->getQueryResults($query, [$this->mapper, 'toEntity']);
    }

    /**
     * @inheritDoc
     */
    public function upsertByTeam(ITeamEntity $team, array $coaches): void
    {
        if ($team->getId() === null) {
            throw new InvalidArgumentException("Upsert players for invalid team");
        }
        $this->db->transactional(function (DatabaseUpdate $dbUpdate) use ($team, $coaches) {
            $dbUpdate->delete($this->mapper->getTableName(), [TeamCoachTableMapper::FIELD_TEAM_ID => $team->getId()]);
            foreach ($coaches as $coach) {
                if ($coach instanceof TeamCoachEntity) {
                    $dbUpdate->insertGeneratedId($this->mapper->getTableName(), $coach);
                } else {
                    throw new InvalidArgumentException(get_class($coach) . " entity is not supported by TeamPlayerRepository");
                }
            }
        });
    }
}
