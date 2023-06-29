<?php


namespace Sportal\FootballApi\Infrastructure\Country;


use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Domain\Country\ICountryRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;

class CountryRepository implements ICountryRepository
{
    private Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findById(int $id): ?ICountryEntity
    {
        $query = $this->db->createQuery(CountryEntity::TABLE_NAME)->where($this->db->andExpression()->eq('id', $id));
        return $this->db->getSingleResult($query, [
            CountryEntity::class,
            "create"
        ]);
    }
}