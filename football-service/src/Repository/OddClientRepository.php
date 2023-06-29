<?php
namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Model\OddClient;
use Sportal\FootballApi\Cache\CacheManager;

class OddClientRepository extends Repository
{

    public function __construct(Connection $conn, CacheManager $cacheManager)
    {
        parent::__construct($conn, $cacheManager);
    }

    public function createObject(array $oddClientArr)
    {
        $oddClient = new OddClient();
        $oddClient->setId($oddClientArr['id'])
            ->setCode($oddClientArr['code'])
            ->setName($oddClientArr['name']);
        
        return $oddClient;
    }

    public function find($id)
    {
        return $this->getByPk($this->getModelClass(), [
            'id' => $id
        ], [
            $this,
            'createObject'
        ]);
    }

    public function findAll($filter = [])
    {
        return $this->getAll($this->getModelClass(), $filter, [
            $this,
            'createObject'
        ]);
    }

    public function getModelClass()
    {
        return OddClient::class;
    }
}