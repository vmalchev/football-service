<?php
namespace Sportal\FootballApi\Cache\Map;

use Sportal\FootballApi\Cache\Cache;
use Sportal\FootballApi\Cache\KeyNotFoundException;
use Predis\Transaction\MultiExec;

class Map
{

    private $client;

    private $cache;

    private $key;

    private $indexName;

    private $className;

    private $manager;

    public function __construct(Cache $cache, $className, $indexName)
    {
        $this->cache = $cache;
        $this->indexName = $indexName;
        $this->className = $className;
        $this->client = $this->cache->getClient();
        $this->manager = $this->cache->getIndexManager();
        $this->key = $this->manager->generateMapKey($className, $indexName);
    }

    public function getAll(callable $populator)
    {
        if (! $this->client->exists($this->key)) {
            $this->populate($populator);
        }
        
        $map = $this->client->hgetall($this->key);
        if (! $this->cache->isEmptyCollection($map)) {
            return $map;
        }
        return [];
    }

    public function getFields(array $fields, callable $populator)
    {
        if (! $this->client->exists($this->key)) {
            $this->populate($populator);
        }
        
        $map = $this->client->hmget($this->key, $fields);
        if (! $this->cache->isEmptyCollection($map)) {
            return $map;
        }
        return [];
    }

    public function createEmpty()
    {
        $this->client->transaction(
            function (MultiExec $tx) {
                $tx->del($this->key);
                $tx->hmset($this->key,
                    [
                        Cache::EMPTY_COLLECTION => Cache::EMPTY_COLLECTION
                    ]);
            });
    }

    public function populate(callable $populator)
    {
        $block = function () use ($populator) {
            $results = $populator();
            if (! empty($results)) {
                $this->manager->rebuild($results, $this->indexName);
            } else {
                $this->createEmpty();
            }
            return true;
        };
        $check = function () {
            return $this->client->exists($this->key);
        };
        $available = $this->cache->createLock($this->key)->executeOnce($block, $check);
        if (! $available) {
            throw new KeyNotFoundException($this->key . " could not be get fetched from cache");
        }
    }
}