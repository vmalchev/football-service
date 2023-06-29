<?php


namespace Sportal\FootballApi\Infrastructure\President;


use Sportal\FootballApi\Domain\President\IPresidentEntity;
use Sportal\FootballApi\Domain\President\IPresidentEntityFactory;
use Sportal\FootballApi\Domain\President\IPresidentRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;

class PresidentRepository implements IPresidentRepository
{
    private Database $db;
    private DatabaseUpdate $dbUpdate;
    private IPresidentEntityFactory $presidentFactory;

    /**
     * PresidentRepository constructor.
     * @param Database $db
     * @param DatabaseUpdate $dbUpdate
     * @param IPresidentEntityFactory $presidentFactory
     */
    public function __construct(Database $db, DatabaseUpdate $dbUpdate, IPresidentEntityFactory $presidentFactory)
    {
        $this->db = $db;
        $this->dbUpdate = $dbUpdate;
        $this->presidentFactory = $presidentFactory;
    }

    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }

    public function findById(string $id): ?IPresidentEntity
    {
        $query = $this->db->createQuery(PresidentTable::TABLE_NAME)->where($this->db->andExpression()->eq('id', $id));

        return $this->db->getSingleResult($query, [$this->presidentFactory, "createFromArray"]);
    }

    public function insert(IPresidentEntity $president): IPresidentEntity
    {
        return $this->dbUpdate->insertGeneratedId(PresidentTable::TABLE_NAME, $president);
    }

    public function update(IPresidentEntity $president): IPresidentEntity
    {
        $this->dbUpdate->update(PresidentTable::TABLE_NAME, $president);
        
        return $this->findById($president->getId());
    }

    public function exists(string $id): bool
    {
        return $this->db->exists(PresidentTable::TABLE_NAME, [PresidentTable::FIELD_ID => $id]);
    }

    public function findByName(string $name): ?IPresidentEntity
    {
        $query = $this->db->createQuery(PresidentTable::TABLE_NAME)->where($this->db->andExpression()->eq('name', $name));

        return $this->db->getSingleResult($query, [$this->presidentFactory, "createFromArray"]);
    }

}