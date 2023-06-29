<?php
namespace Sportal\FootballApi\Cache;

use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Cache\Map\MapUpdate;
use Sportal\FootballApi\Cache\Index\IndexManager;
use Sportal\FootballApi\Cache\Entity\CacheEntityInterface;

class CollectionManager
{

    private $cache;

    private $client;

    private $nameUtil;

    /**
     *
     * @var CollectionUpdateInterface
     */
    private $collections;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->client = $this->cache->getClient();
        $this->nameUtil = $cache->getNameUtil();
        $this->collections = $this->createCollections();
    }

    public function rebuild(array $instances, $name = null)
    {
        $queues = [];
        $collections = $this->createCollections();
        
        foreach ($instances as $instance) {
            $className = get_class($instance);
            if ($instance instanceof CacheEntityInterface) {
                if (! isset($queues[$className])) {
                    $queues[$className] = $this->cache->getEntityManager($className)->createUpdateQueue();
                }
                $queues[$className]->add($instance);
            }
            foreach ($collections as $collection) {
                if (is_subclass_of($instance, $collection->getClassName())) {
                    $collection->add($instance, $name);
                }
            }
        }
        
        $this->client->transaction(
            function (MultiExec $tx) use ($collections, $queues) {
                foreach ($queues as $queue) {
                    $queue->flush($tx);
                }
                foreach ($collections as $collection) {
                    $collection->flush($tx, [], true);
                }
            });
    }

    /**
     *
     * @param boolean $shouldCreate
     */
    public function flush($shouldCreate = false, callable $chainTx = null)
    {
        if ($this->hasUpdates()) {
            try {
                if ($shouldCreate) {
                    $this->flushAll($chainTx);
                } else {
                    $this->flushExisting($chainTx);
                }
            } finally {
                $this->cancel();
            }
        }
    }

    public function hasUpdates()
    {
        foreach ($this->collections as $collection) {
            if ($collection->hasUpdates()) {
                return true;
            }
        }
        return false;
    }

    public function cancel()
    {
        foreach ($this->collections as $collection) {
            $collection->cancel();
        }
    }

    public function add($instance)
    {
        foreach ($this->collections as $collection) {
            if (is_subclass_of($instance, $collection->getClassName())) {
                $collection->add($instance);
            }
        }
    }

    public function delete($instance)
    {
        foreach ($this->collections as $collection) {
            if (is_subclass_of($instance, $collection->getClassName())) {
                $collection->delete($instance);
            }
        }
    }

    public function update($existing, $updated)
    {
        foreach ($this->collections as $collection) {
            if (is_subclass_of($updated, $collection->getClassName())) {
                $collection->update($existing, $updated);
            }
        }
    }

    public function generateIndexKey($className, $name)
    {
        return $this->collections['index']->generateKey($className, $name);
    }

    public function generateMapKey($className, $name)
    {
        return $this->collections['map']->generateKey($className, $name);
    }

    private function flushAll($chainTx = null)
    {
        $this->client->transaction(
            function ($tx) use ($chainTx) {
                foreach ($this->collections as $collection) {
                    $collection->flush($tx, [], true);
                }
                if ($chainTx !== null) {
                    $chainTx($tx);
                }
            });
    }

    private function flushExisting($chainTx = null)
    {
        $keys = [];
        foreach ($this->collections as $collection) {
            $keys = array_merge($keys, $collection->getUpdateKeys());
        }
        
        $options = [
            'cas' => true,
            'watch' => $keys,
            'retry' => 3
        ];
        $this->client->transaction($options,
            function ($tx) use ($chainTx, $keys) {
                $existsMap = [];
                foreach ($keys as $key) {
                    $existsMap[$key] = $tx->exists($key);
                }
                $tx->multi();
                foreach ($this->collections as $collection) {
                    $collection->flush($tx, $existsMap, false);
                }
                if ($chainTx !== null) {
                    $chainTx($tx);
                }
            });
    }

    /**
     *
     * @return \Sportal\FootballApi\Cache\CollectionUpdateInterface[]
     */
    private function createCollections()
    {
        return [
            'map' => new MapUpdate($this->nameUtil),
            'index' => new IndexManager($this->cache)
        ];
    }
}