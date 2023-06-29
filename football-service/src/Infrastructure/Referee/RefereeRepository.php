<?php


namespace Sportal\FootballApi\Infrastructure\Referee;


use Sportal\FootballApi\Domain\Referee\IRefereeEntity;
use Sportal\FootballApi\Domain\Referee\IRefereeEntityFactory;
use Sportal\FootballApi\Domain\Referee\IRefereeRepository;
use Sportal\FootballApi\Domain\Referee\RefereeFilter;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;
use Sportal\FootballApi\Infrastructure\Match\MatchTableMapper;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;

class RefereeRepository implements IRefereeRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private IRefereeEntityFactory $refereeFactory;

    /**
     * RefereeRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param IRefereeEntityFactory $refereeFactory
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate, IRefereeEntityFactory $refereeFactory)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->refereeFactory = $refereeFactory;
    }


    public function insert(IRefereeEntity $referee): IRefereeEntity
    {
        return $this->dbUpdate->insertGeneratedId(RefereeTable::TABLE_NAME, $referee);
    }

    public function update(IRefereeEntity $referee): IRefereeEntity
    {
        $this->dbUpdate->update(RefereeTable::TABLE_NAME, $referee);
        return $referee;
    }

    public function findAll(RefereeFilter $refereeFilter): array
    {
        $query = $this->db->createQuery(RefereeTable::TABLE_NAME)
            ->addOrderBy(RefereeTable::FIELD_CREATED_AT, SortDirection::DESC)
            ->addOrderBy(RefereeTable::FIELD_ID, SortDirection::DESC)
            ->addJoin($this->createCountryJoin());

        if (!empty($refereeFilter->getSeasonIds())) {
            $joinFactory = $this->db->getJoinFactory();
            $matchJoin = $joinFactory
                ->createLeft(MatchTableMapper::TABLE_NAME, [])
                ->setForeignKey(RefereeTable::FIELD_ID)
                ->setReference(MatchTableMapper::FIELD_REFEREE_ID)
                ->addChild($joinFactory->createLeft(StageTableMapper::TABLE_NAME, []));
            $query->addJoin($matchJoin)
                ->distinct()
                ->where($this->db->andExpression()->in(StageTableMapper::FIELD_SEASON_ID, $refereeFilter->getSeasonIds(), StageTableMapper::TABLE_NAME));
        }

        return $this->db->getPagedQueryResults($query, [$this->refereeFactory, "createFromArray"])->getData();
    }

    private function createCountryJoin(): Join
    {
        return $this->db->getJoinFactory()
            ->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())
            ->setFactory([CountryEntity::class, 'create']);
    }

    public function findById(string $id): ?IRefereeEntity
    {
        $query = $this->db->createQuery(RefereeTable::TABLE_NAME)
            ->where($this->db->andExpression()->eq('id', $id))
            ->addJoin($this->createCountryJoin());

        return $this->db->getSingleResult($query, [$this->refereeFactory, "createFromArray"]);
    }

    public function exists(string $id): bool
    {
        return $this->db->exists(RefereeTable::TABLE_NAME, [RefereeTable::FIELD_ID => $id]);
    }
}
