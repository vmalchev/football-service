<?php
namespace Sportal\FootballApi\Cache\Index;

use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Cache\Cache;
use Sportal\FootballApi\Cache\Index\IndexableInterface;
use Sportal\FootballApi\Cache\CollectionUpdateInterface;

class IndexManager implements CollectionUpdateInterface
{

    private $cache;

    private $sortedAdd;

    private $sortedRem;

    private $nameUtil;

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
        $this->nameUtil = $cache->getNameUtil();
        $this->sortedAdd = [];
        $this->sortedRem = [];
    }

    public function cancel()
    {
        $this->sortedAdd = [];
        $this->sortedRem = [];
    }

    public function hasUpdates()
    {
        return ! empty($this->sortedAdd) || ! empty($this->sortedRem);
    }

    public function delete($existing)
    {
        $sets = $existing->getSortedIndecies();
        $this->removeFromSorted($existing, array_keys($sets));
    }

    public function add($instance, $name = null)
    {
        $sets = $instance->getSortedIndecies();
        foreach ($sets as $index => $value) {
            if (empty($name) || $name == $index) {
                $this->addToSorted($instance, $index, $value);
            }
        }
    }

    public function update($existing, $updated)
    {
        $existingSets = $existing->getSortedIndecies();
        $updatedSets = $updated->getSortedIndecies();
        $primaryChanged = false;
        if ($existing->getPrimaryKeyMap() != $updated->getPrimaryKeyMap()) {
            $removed = array_keys($existingSets);
            $primaryChanged = true;
        } else {
            $removed = array_diff(array_keys($existingSets), array_keys($updatedSets));
        }
        $this->removeFromSorted($existing, $removed);
        foreach ($updatedSets as $index => $value) {
            if ($primaryChanged || ! isset($existingSets[$index]) || $existingSets[$index] != $value) {
                $this->addToSorted($updated, $index, $value);
            }
        }
    }

    public function flush(MultiExec $tx, array $existsMap, $shouldCreate)
    {
        if (! empty($this->sortedAdd)) {
            foreach ($this->sortedAdd as $key => $dict) {
                if ($shouldCreate || ! empty($existsMap[$key])) {
                    $tx->zrem($key, Cache::EMPTY_COLLECTION);
                    $tx->zadd($key, $dict);
                }
            }
        }
        if (! empty($this->sortedRem)) {
            foreach ($this->sortedRem as $key => $members) {
                $tx->zrem($key, $members);
            }
        }
    }

    public function generateKey($className, $indexName)
    {
        return $this->nameUtil->persistanceName($className) . Cache::DELIM . 'index' . Cache::DELIM . $indexName;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\CollectionUpdateInterface::getClassName()
     */
    public function getClassName()
    {
        return IndexableInterface::class;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\CollectionUpdateInterface::getUpdateKeys()
     */
    public function getUpdateKeys()
    {
        if (! empty($this->sortedAdd)) {
            return array_keys($this->sortedAdd);
        }
        return [];
    }

    private function addToSorted(IndexableInterface $instance, $index, $value)
    {
        $em = $this->cache->getEntityManager(get_class($instance));
        if ($value instanceof \DateTime) {
            $value = $this->cache->formatTime($value);
        }
        $indexKey = $this->generateKey($instance, $index);
        $this->sortedAdd[$indexKey][$em->generateKey($instance)] = $value;
    }

    private function removeFromSorted(IndexableInterface $existing, array $indexNames)
    {
        $em = $this->cache->getEntityManager(get_class($existing));
        foreach ($indexNames as $indexName) {
            $indexKey = $this->generateKey($existing, $indexName);
            $this->sortedRem[$indexKey][] = $em->generateKey($existing);
        }
    }
}