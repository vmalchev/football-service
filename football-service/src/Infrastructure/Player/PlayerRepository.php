<?php


namespace Sportal\FootballApi\Infrastructure\Player;


use Sportal\FootballApi\Domain\Player\IPlayerCollection;
use Sportal\FootballApi\Domain\Player\IPlayerEditEntity;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Domain\Player\IPlayerRepository;
use Sportal\FootballApi\Infrastructure\City\CityEntityFactory;
use Sportal\FootballApi\Infrastructure\City\CityTable;
use Sportal\FootballApi\Infrastructure\City\CityTableMapper;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\SortDirection;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;

class PlayerRepository implements IPlayerRepository
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
     * @var CityEntityFactory
     */
    private CityEntityFactory $cityFactory;

    private PlayerTableMapper $tableMapper;

    /**
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param CityEntityFactory $cityFactory
     * @param CityTableMapper $cityMapper
     * @param CountryTableMapper $countryMapper
     * @param PlayerTableMapper $tableMapper
     */
    public function __construct(Database $db,
                                DatabaseUpdate $dbUpdate,
                                CityEntityFactory $cityFactory,
                                PlayerTableMapper $tableMapper)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->cityFactory = $cityFactory;
        $this->tableMapper = $tableMapper;
    }

    public function findAll(): array
    {
        $countryJoin = $this->db->getJoinFactory()
            ->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())
            ->setFactory([CountryEntity::class, 'create']);

        $cityJoin = $this->db->getJoinFactory()
            ->createLeft(CityTable::TABLE_NAME, CityTable::getColumns())
            ->setFactory([$this->cityFactory, 'createFromArray']);

        $query = $this->db->createQuery(PlayerTable::TABLE_NAME)
            ->addOrderBy(PlayerTable::FIELD_CREATED_AT, SortDirection::DESC)
            ->addOrderBy(PlayerTable::FIELD_ID, SortDirection::DESC)
            ->addJoin($countryJoin)
            ->addJoin($cityJoin);

        return $this->db->getPagedQueryResults($query, [
            PlayerEntity::class,
            "create"
        ])->getData();
    }

    /**
     * @param string $id
     * @return IPlayerEntity|null
     */
    public function findById(string $id): ?IPlayerEntity
    {
        $countryJoin = $this->db->getJoinFactory()->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())->setFactory([CountryEntity::class, 'create']);
        $cityJoin = $this->db->getJoinFactory()->createLeft(CityTable::TABLE_NAME, CityTable::getColumns())->setFactory([$this->cityFactory, 'createFromArray']);
        $query = $this->db->createQuery(PlayerTable::TABLE_NAME)->where($this->db->andExpression()->eq('id', $id))->addJoin($countryJoin)->addJoin($cityJoin);

        return $this->db->getSingleResult($query, [
            PlayerEntity::class,
            "create"
        ]);
    }

    /**
     * @param IPlayerEditEntity $player
     * @return IPlayerEntity
     */
    public function update(IPlayerEditEntity $player): IPlayerEntity
    {
        $this->dbUpdate->update(PlayerTable::TABLE_NAME, $player);

        return $this->findById($player->getId());
    }

    /**
     * @param IPlayerEditEntity $player
     * @return IPlayerEditEntity
     */
    public function insert(IPlayerEditEntity $player): IPlayerEditEntity
    {
        return $this->dbUpdate->insertGeneratedId(PlayerTable::TABLE_NAME, $player);
    }

    public function exists(string $id): bool
    {
        return $this->db->exists(PlayerTable::TABLE_NAME, [PlayerTable::FIELD_ID => $id]);
    }

    /**
     * @param string[] $ids
     * @return IPlayerCollection
     */
    public function findByIds(array $ids): IPlayerCollection
    {
        if (!empty($ids)) {
            $query = $this->db->createQuery(PlayerTable::TABLE_NAME)
                ->where($this->db->andExpression()->in(PlayerTable::FIELD_ID, $ids))
                ->addJoin($this->tableMapper->getCityJoin())
                ->addJoin($this->tableMapper->getCountryJoin());
            $results = $this->db->getQueryResults($query, [$this->tableMapper, 'create']);
            return new PlayerCollection($results);
        } else {
            return new PlayerCollection([]);
        }
    }
}