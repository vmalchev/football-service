<?php


namespace Sportal\FootballApi\Infrastructure\City;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\City\ICityEntityFactory;
use Sportal\FootballApi\Domain\City\ICityRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;

class CityRepository implements ICityRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private ICityEntityFactory $cityFactory;

    /**
     * CityRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param ICityEntityFactory $cityFactory
     */
    public function __construct(
        Database $db,
        DatabaseUpdate $dbUpdate,
        ICityEntityFactory $cityFactory
    ){
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->cityFactory = $cityFactory;
    }

    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }

    public function insert(ICityEntity $city): ICityEntity
    {
        return $this->dbUpdate->insertGeneratedId(CityTable::TABLE_NAME, $city);
    }

    public function update(ICityEntity $city): ?ICityEntity
    {
        if ($this->dbUpdate->update(CityTable::TABLE_NAME, $city) === 0) {
            return null;
        }

        return $this->findById($city->getId());
    }

    public function findById(string $id): ?ICityEntity
    {
        $query = $this->db->createQuery(CityTable::TABLE_NAME)
            ->where($this->db->andExpression()->eq('id', $id))
            ->addJoin(
                $this->db->getJoinFactory()
                    ->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())
                    ->setFactory([CountryEntity::class, 'create'])
            );

        return $this->db->getSingleResult($query, [$this->cityFactory, "createFromArray"]);
    }

    public function exists(string $id): bool
    {
        return $this->db->exists(CityTable::TABLE_NAME, [CityTable::FIELD_ID => $id]);
    }

    public function findByUniqueConstraint(string $name, string $country_id): ?ICityEntity
    {
        $query = $this->db->createQuery(CityTable::TABLE_NAME)
            ->where(
                $this->db->andExpression()
                    ->eq('name', $name)
                    ->eq('country_id', $country_id)
            )
            ->addJoin($this->createCountryJoin());

        return $this->db->getSingleResult($query, [$this->cityFactory, "createFromArray"]);
    }

    private function createCountryJoin(): Join
    {
        return $this->db->getJoinFactory()
            ->createInner(CountryEntity::TABLE_NAME, CountryEntity::columns())
            ->setFactory([CountryEntity::class, 'create']);
    }
}