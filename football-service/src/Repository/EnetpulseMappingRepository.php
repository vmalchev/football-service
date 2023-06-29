<?php
namespace Sportal\FootballApi\Repository;

use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Util\NameUtil;
use Predis\Client;

class EnetpulseMappingRepository implements MappingRepositoryInterface
{

    /**
     *
     * @var Client
     */
    protected $cache;

    /**
     *
     * @var Connection
     */
    protected $conn;

    private $sourceName;

    public function __construct(Connection $conn, Client $cache, $sourceName)
    {
        $this->conn = $conn;
        $this->cache = $cache;
        $this->sourceName = $sourceName;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::getOwnId()
     */
    public function getOwnId($className, $remoteId)
    {
        $key = $this->getFeedMapKey($className);
        if (! $this->cache->exists($key)) {
            $this->popuplateMap($className);
        }

        return $this->cache->hget($key, $remoteId);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::getRemoteIds()
     */
    public function getRemoteIds($className)
    {
        $key = $this->getOwnMapKey($className);
        if (! $this->cache->exists($key)) {
            $this->popuplateMap($className);
        }
        $array = $this->cache->hgetall($key);
        unset($array[0]);
        if ($array !== null) {
            return array_values($array);
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::getRemoteId()
     */
    public function getRemoteId($className, $ownId)
    {
        $key = $this->getOwnMapKey($className);
        if (! $this->cache->exists($key)) {
            $this->popuplateMap($className);
        }

        return $this->cache->hget($key, $ownId);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::setMapping()
     */
    public function setMapping($className, $remoteId, $ownId)
    {
        $remoteId = (int) $remoteId;
        $ownId = (int) $ownId;
        $remoteKey = $this->sourceName . "_id";
        $primaryKey = [
            'entity' => $this->getPersistanceName($className),
            'entity_id' => $ownId,
            $remoteKey => $remoteId
        ];

        $tableName = 'id_mapping_' . $this->sourceName;
        if (($existing = $this->getOwnId($className, $remoteId)) === null) {
            $this->conn->insert($tableName, $primaryKey);
        } else {
            unset($primaryKey['entity_id']);
            $this->conn->update($tableName, [
                'entity_id' => $ownId
            ], $primaryKey);
            $this->cache->hdel($this->getOwnMapKey($className), $existing);
        }
        $this->cache->hset($this->getOwnMapKey($className), $ownId, $remoteId);
        $this->cache->hset($this->getFeedMapKey($className), $remoteId, $ownId);
    }

    protected function getPersistanceName($className)
    {
        return NameUtil::camel2underscore(NameUtil::shortClassName(is_object($className) ? get_class($className) : $className));
    }

    protected function getOwnMapKey($className)
    {
        $name = $this->getPersistanceName($className);
        return $this->sourceName . "_" . $name . "_id";
    }

    protected function getFeedMapKey($className)
    {
        $name = $this->getPersistanceName($className);
        return $name . "_id_" . $this->sourceName;
    }

    /**
     *
     * @param string $entity
     * @return integer[]
     */
    protected function getMap($entity)
    {
        $qb = $this->conn->createQueryBuilder();
        $stmt = $qb->select([
            'entity_id',
            $this->sourceName . "_id as feed_id"
        ])
            ->from('id_mapping_' . $this->sourceName)
            ->where('entity = :entity')
            ->setParameter('entity', $entity)
            ->execute();
        $map = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $map[$row['entity_id']] = $row['feed_id'];
        }
        return $map;
    }

    protected function popuplateMap($className)
    {
        $map = $this->getMap($this->getPersistanceName($className));
        $this->setRedisMap($this->getOwnMapKey($className), $map);
        $feedMap = array_combine(array_values($map), array_keys($map));
        $this->setRedisMap($this->getFeedMapKey($className), $feedMap);
    }

    protected function setRedisMap($key, array $map)
    {
        $batches = array_chunk($map, 100000, true);
        foreach ($batches as $batch) {
            $this->cache->hmset($key, $batch);
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Repository\MappingRepositoryInterface::getSourceName()
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    public function getEntities()
    {
        $qb = $this->conn->createQueryBuilder();
        $stmt = $qb->select('DISTINCT(entity)')
            ->from('id_mapping_' . $this->sourceName)
            ->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getOwnMap($className)
    {
        $key = $this->getOwnMapKey($className);
        if (! $this->cache->exists($key)) {
            $this->popuplateMap($className);
        }

        $data = $this->cache->hgetall($key);
        unset($data[0]);
        return $data;
    }

    public function getRemoteMap($className)
    {
        $key = $this->getFeedMapKey($className);
        if (! $this->cache->exists($key)) {
            $this->popuplateMap($className);
        }

        $data = $this->cache->hgetall($key);
        unset($data[0]);
        return $data;
    }
}