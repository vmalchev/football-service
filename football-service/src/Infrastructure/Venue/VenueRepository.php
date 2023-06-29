<?php


namespace Sportal\FootballApi\Infrastructure\Venue;


use Sportal\FootballApi\Domain\City\ICityEntityFactory;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;
use Sportal\FootballApi\Domain\Venue\IVenueRepository;
use Sportal\FootballApi\Domain\Venue\VenueFilter;
use Sportal\FootballApi\Infrastructure\City\CityTable;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;
use Sportal\FootballApi\Infrastructure\Match\MatchTableMapper;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;

class VenueRepository implements IVenueRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private IVenueEntityFactory $venueFactory;
    private ICityEntityFactory $cityFactory;

    public function __construct(
        Database $db,
        DatabaseUpdate $dbUpdate,
        IVenueEntityFactory $venueFactory,
        ICityEntityFactory $cityFactory
    )
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->venueFactory = $venueFactory;
        $this->cityFactory = $cityFactory;
    }

    public function insert(IVenueEntity $venue): IVenueEntity
    {
        return $this->dbUpdate->insertGeneratedId(VenueTable::TABLE_NAME, $venue);
    }

    public function update(IVenueEntity $venue): IVenueEntity
    {
        $this->dbUpdate->update(VenueTable::TABLE_NAME, $venue);
        return $venue;
    }

    public function findById(string $id): ?IVenueEntity
    {
        $query = $this->db->createQuery(VenueTable::TABLE_NAME)
            ->where($this->db->andExpression()->eq('id', $id))
            ->addJoin($this->createCountryJoin())
            ->addJoin(
                $this->db->getJoinFactory()
                    ->createLeft(CityTable::TABLE_NAME, CityTable::getColumns())
                    ->addChild($this->createCountryJoin())
                    ->setFactory([$this->cityFactory, 'createFromArray'])
            );

        return $this->db->getSingleResult($query, [$this->venueFactory, "createFromArray"]);
    }

    private function createCountryJoin(): Join
    {
        return $this->db->getJoinFactory()
            ->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())
            ->setFactory([CountryEntity::class, 'create']);
    }

    public function findAll(VenueFilter $filter): array
    {
        $query = $this->db->createQuery(VenueTable::TABLE_NAME)
            ->addOrderBy(VenueTable::FIELD_CREATED_AT, SortDirection::DESC)
            ->addOrderBy(VenueTable::FIELD_ID, SortDirection::DESC)
            ->addJoin($this->createCountryJoin())
            ->addJoin(
                $this->db->getJoinFactory()
                    ->createLeft(CityTable::TABLE_NAME, CityTable::getColumns())
                    ->setFactory([$this->cityFactory, 'createFromArray'])
            );

        if (!empty($filter->getSeasonIds())) {
            $joinFactory = $this->db->getJoinFactory();
            $matchJoin = $joinFactory
                ->createLeft(MatchTableMapper::TABLE_NAME, [])
                ->setForeignKey(VenueTable::FIELD_ID)
                ->setReference(MatchTableMapper::FIELD_VENUE_ID)
                ->addChild($joinFactory->createLeft(StageTableMapper::TABLE_NAME, []));
            $query->addJoin($matchJoin)
                ->distinct()
                ->where($this->db->andExpression()->in(StageTableMapper::FIELD_SEASON_ID, $filter->getSeasonIds(), StageTableMapper::TABLE_NAME));
        }


        return $this->db->getPagedQueryResults($query, [$this->venueFactory, "createFromArray"])->getData();
    }

    public function exists(string $id): bool
    {
        return $this->db->exists(VenueTable::TABLE_NAME, [VenueTable::FIELD_ID => $id]);
    }

    public function findByUniqueConstraint(string $name, string $countryId, ?string $cityId): ?IVenueEntity
    {
        $query = $this->db->createQuery(VenueTable::TABLE_NAME)
            ->where(
                $this->db->andExpression()
                    ->eq('name', $name)
                    ->eq('country_id', $countryId)
                    ->eq('city_id', $cityId)
            )
            ->addJoin($this->createCountryJoin());

        return $this->db->getSingleResult($query, [$this->venueFactory, "createFromArray"]);
    }
}