<?php


namespace Sportal\FootballApi\Infrastructure\Entity;


use Sportal\FootballApi\Domain\Entity\IEntityExistsRepository;
use Sportal\FootballApi\Infrastructure\Database\Database;

class EntityExistsRepository implements IEntityExistsRepository
{
    /**
     * @var Database
     */
    private $db;

    /**
     * EntityExistsRepository constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }


    public function exists($tableName, $id): bool
    {
        return $this->db->exists($tableName, array('id' => $id));
    }
}