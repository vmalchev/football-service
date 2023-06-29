<?php


namespace Sportal\FootballApi\Infrastructure\Coach;


use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Coach\ICoachEntityFactory;
use Sportal\FootballApi\Domain\Coach\ICoachRepository;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;
use Sportal\FootballApi\Infrastructure\TeamSquad\TeamCoachTableMapper;

class CoachRepository implements ICoachRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private ICoachEntityFactory $coachFactory;

    public function __construct(Database $db, DatabaseUpdate $dbUpdate, ICoachEntityFactory $coachFactory)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->coachFactory = $coachFactory;
    }

    public function findAll(): array
    {
        $query = $this->db->createQuery(CoachTable::TABLE_NAME)
            ->addOrderBy(CoachTable::FIELD_CREATED_AT, SortDirection::DESC)
            ->addOrderBy(CoachTable::FIELD_ID, SortDirection::DESC)
            ->addJoin($this->createCountryJoin());

        return $this->db->getPagedQueryResults($query, [$this->coachFactory, "createFromArray"])->getData();
    }

    private function createCountryJoin(): Join
    {
        return $this->db->getJoinFactory()
            ->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())
            ->setFactory([CountryEntity::class, 'create']);
    }

    public function findById(string $id): ?ICoachEntity
    {
        $query = $this->db->createQuery(CoachTable::TABLE_NAME)
            ->where($this->db->andExpression()->eq('id', $id))
            ->addJoin($this->createCountryJoin());

        return $this->db->getSingleResult($query, [$this->coachFactory, "createFromArray"]);
    }

    public function insert(ICoachEntity $coach): ICoachEntity
    {
        return $this->dbUpdate->insertGeneratedId(CoachTable::TABLE_NAME, $coach);
    }

    public function update(ICoachEntity $coach): ICoachEntity
    {
        $this->dbUpdate->update(CoachTable::TABLE_NAME, $coach);
        return $coach;
    }

    public function delete(int $id)
    {
        $this->db->delete(CoachTable::TABLE_NAME, [CoachTable::FIELD_ID => $id]);
    }

    public function exists(string $id): bool
    {
        return $this->db->exists(CoachTable::TABLE_NAME, [CoachTable::FIELD_ID => $id]);
    }

    /**
     * @param ITeamEntity $teamEntity
     * @return ICoachEntity
     */
    public function findCurrentCoachByTeam(ITeamEntity $teamEntity): ?ICoachEntity
    {
        $coachJoin = $this->db->getJoinFactory()
            ->createInner(CoachTable::TABLE_NAME, CoachTable::getColumns(), TeamCoachTableMapper::FIELD_COACH_ID);

        $query = $this->db->createQuery(TeamCoachTableMapper::TABLE_NAME)
            ->addJoin($coachJoin->addChild($this->createCountryJoin()))
            ->where(
                $this->db->andExpression()
                    ->eq(TeamCoachTableMapper::FIELD_TEAM_ID, $teamEntity->getId())
                    ->eq(TeamCoachTableMapper::FIELD_ACTIVE, 1)
            )
        ;

        $teamCoach = $this->db->getSingleResult($query);
        return isset($teamCoach['coach']) ? $this->coachFactory->createFromArray($teamCoach['coach']) : null;
    }
}
