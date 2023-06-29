<?php
namespace Sportal\FootballApi\Cache\Entity;

use Predis\Client;
use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Cache\Cache;
use Sportal\FootballApi\Cache\Index\IndexManager;
use Sportal\FootballApi\Cache\KeyNotFoundException;
use Sportal\FootballApi\Util\ModelNameUtil;

class EntityManager
{

    /**
     *
     * @var Client
     */
    private $client;

    /**
     *
     * @var IndexManager
     */
    private $indexManager;

    /**
     *
     * @var ModelNameUtil
     */
    private $nameUtil;

    private $inserts;

    /**
     *
     * @var array
     */
    private $updates;

    /**
     *
     * @var array
     */
    private $deletes;

    private $serializer;

    /**
     *
     * @var Cache
     */
    private $cache;

    function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->client = $cache->getClient();
        $this->indexManager = $cache->getIndexManager();
        $this->nameUtil = $cache->getNameUtil();
        $this->serializer = $cache->getSerializer();
    }

    protected function create(CacheEntityInterface $instance)
    {
        $this->updates[$this->generateKey($instance)] = $this->encode($instance);
        $this->indexManager->add($instance);
    }

    public function add(CacheEntityInterface $instance)
    {
        $this->inserts[] = $instance;
    }

    public function update(CacheEntityInterface $existing, CacheEntityInterface $updated)
    {
        $this->updates[$this->generateKey($updated)] = $this->encode($updated);
        if ($updated->getPrimaryKeyMap() != $existing->getPrimaryKeyMap()) {
            $this->deletes[] = $this->generateKey($existing);
        }
        $this->indexManager->update($existing, $updated);
    }

    public function delete(CacheEntityInterface $instance)
    {
        $this->deletes[] = $this->generateKey($instance);
        $this->indexManager->delete($instance);
    }

    public function encode($instance)
    {
        return $this->serializer->serialize($instance);
    }

    public function cancel()
    {
        $this->inserts = [];
        $this->updates = [];
        $this->deletes = [];
    }

    public function hasUpates()
    {
        return (! empty($this->updates) || ! empty($this->deletes) || ! empty($this->inserts));
    }

    public function flush($allowNewIndexes = false)
    {
        $update = function (MultiExec $tx) {
            $this->setAll($tx, $this->updates);
            $this->deleteAll($tx, $this->deletes);
        };
        try {
            if (! empty($this->inserts)) {
                foreach ($this->inserts as $instance) {
                    $this->create($instance);
                }
            }
            if ($this->indexManager->hasUpdates()) {
                $this->indexManager->flush($allowNewIndexes, $update);
            } else {
                $this->client->transaction($update);
            }
        } finally {
            $this->cancel();
        }
    }

    public function generateKey(CacheEntityInterface $instance)
    {
        return $this->getInstanceKey(get_class($instance), $instance->getPrimaryKeyMap());
    }

    public function createUpdateQueue()
    {
        return new EntityUpdateQueue($this);
    }

    public function getMany(array $keys)
    {
        $items = $this->client->mget($keys);
        $results = [];
        if (! empty($items)) {
            foreach ($items as $item) {
                if ($item !== null && ($instance = $this->serializer->unserialize($item)) !== null) {
                    $results[] = $instance;
                }
            }
        }
        return $results;
    }

    public function getSingle($className, $primaryKey, callable $populator)
    {
        $key = $this->getInstanceKey($className, $primaryKey);
        try {
            return $this->get($key);
        } catch (KeyNotFoundException $e) {
            $block = function () use ($populator, $key) {
                $instance = $populator();
                $this->client->set($key, $this->encode($instance));
                return $instance;
            };
            $getResult = function () use ($key) {
                return $this->get($key);
            };
            return $this->cache->createLock($key)->executeOnce($block, $getResult);
        }
    }

    public function setAll(MultiExec $tx, $updates)
    {
        if (! empty($updates)) {
            $tx->mset($updates);
        }
    }

    protected function deleteAll(MultiExec $tx, $deletes)
    {
        if (! empty($deletes)) {
            $tx->del($deletes);
        }
    }

    protected function getInstanceKey($className, $primaryKey)
    {
        $prefix = $this->nameUtil->persistanceName($className) . Cache::DELIM;
        if (is_array($primaryKey)) {
            return $prefix . implode('-', $primaryKey);
        }
        
        return $prefix . $primaryKey;
    }

    protected function get($key)
    {
        $str = $this->client->get($key);
        if ($str !== null) {
            return $this->serializer->unserialize($str);
        }
        throw new KeyNotFoundException("$key not found in cache");
    }
}