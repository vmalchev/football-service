<?php
namespace Sportal\FootballApi\Cache\Index;

use Predis\Client;
use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Cache\Cache;
use Sportal\FootballApi\Cache\KeyNotFoundException;

class SortedIndex
{

    private $client;

    private $cache;

    private $key;

    private $indexName;

    private $className;

    public function __construct(Cache $cache, $className, $indexName)
    {
        $this->cache = $cache;
        $this->indexName = $indexName;
        $this->className = $className;
        $this->client = $this->cache->getClient();
        $this->key = $this->cache->getIndexManager()->generateIndexKey($className, $indexName);
    }

    public function queryByScore($min, $max, callable $populator, $reverse = false)
    {
        $range = $this->getMembersByScore($min, $max, $populator, $reverse);
        if (! empty($range)) {
            return $this->cache->getEntityManager($this->className)->getMany($range);
        }
        return $range;
    }

    public function createEmpty()
    {
        $this->client->transaction(
            function (MultiExec $tx) {
                $tx->del($this->key);
                $tx->zadd($this->key, [
                    Cache::EMPTY_COLLECTION => 0
                ]);
            });
    }

    public function populate(callable $populator)
    {
        $block = function () use ($populator) {
            $results = $populator();
            if (! empty($results)) {
                $this->cache->getIndexManager()->rebuild($results, $this->indexName);
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

    public function getAllByScore(callable $populator, $reverse = false)
    {
        return $this->queryByScore('-inf', '+inf', $populator, $reverse);
    }

    public function getMembersByScore($min, $max, callable $populator, $reverse = false)
    {
        if (! $this->client->exists($this->key)) {
            $this->populate($populator);
        }
        
        if ($reverse) {
            $range = $this->client->zrevrangebyscore($this->key, $max, $min);
        } else {
            $range = $this->client->zrangebyscore($this->key, $min, $max);
        }
        
        if (! $this->cache->isEmptyCollection($range)) {
            return $range;
        }
        
        return [];
    }
}