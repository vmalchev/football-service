<?php
namespace Sportal\FootballApi\Cache;

use Predis\Client;
use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Model\ModelInterface;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Util\NameUtil;

class CacheManager
{

    const BLOCKER_TIMEOUT = 10;

    const BLOCKER_SUFFIX = "-blocker";

    const FILTER_SUFFIX = "-filter";

    const LIST_DELIM = ",";

    const COLUMN_SUFFIX = "-columns";

    protected $redis;

    protected $logger;

    protected $indexManager;

    public function __construct(Client $redis, LoggerInterface $logger)
    {
        $this->redis = $redis;
        $this->logger = $logger;
    }

    public function getInstance($persistentName, array $primaryKeys)
    {
        $str = $this->redis->hget($persistentName, self::getPersistanceId($primaryKeys));
        if ($str !== null) {
            return unserialize($str);
        }
        return null;
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    public function waitDel($key)
    {
        $retries = 0;
        while ($this->redis->exists($key) && $retries < 8) {
            sleep(1);
            $retries ++;
            $this->logger->info(NameUtil::shortClassName(self::class) . ": Waiting for $key to be deleted: $retries");
        }
    }

    private function populateCache($blockerKey, callable $setter, callable $populator)
    {
        $options = array(
            'cas' => true, // Make the transaction watch for changes
            'watch' => $blockerKey, // Key that needs to be WATCHed to detect changes
            'retry' => 2
        );
        
        $result = null;
        $locked = false;
        
        try {
            $this->redis->transaction($options,
                function (MultiExec $tx) use ($blockerKey, &$locked) {
                    if (! $tx->exists($blockerKey)) {
                        $tx->multi();
                        $tx->set($blockerKey, true, 'ex', self::BLOCKER_TIMEOUT);
                        $locked = true;
                    }
                });
        } catch (Exception $e) {
            $this->logger->info(
                NameUtil::shortClassName(self::class) . ": " . "Caught exception while trying to set: " . $blockerKey .
                     ", message: " . $e->getMessage() . ", class: " . get_class($e));
        }
        
        if ($locked) {
            $this->logger->info(NameUtil::shortClassName(self::class) . ": Locked: $blockerKey");
            $result = $populator();
            $this->redis->transaction(
                function (MultiExec $tx) use ($setter, $blockerKey, $result) {
                    $setter($tx, $result);
                    $tx->del([
                        $blockerKey
                    ]);
                });
            return $result;
        }
        $this->waitDel($blockerKey);
        return null;
    }

    public function populateInstance($persistentName, array $primaryKeys, callable $populator)
    {
        $persistId = self::getPersistanceId($primaryKeys);
        $blockerKey = $persistentName . '-' . $persistId . self::BLOCKER_SUFFIX;
        $setter = function ($tx, $result) use ($persistentName, $persistId) {
            if ($result !== null) {
                $tx->hset($persistentName, $persistId, serialize($result));
            }
        };
        $result = $this->populateCache($blockerKey, $setter, $populator);
        if ($result === null) {
            $result = $this->redis->hget($persistentName, $persistId);
            return $result !== null ? unserialize($result) : null;
        }
        return $result;
    }

    public function setInstance($persitanceName, array $primaryKeys, $instance)
    {
        $persistId = self::getPersistanceId($primaryKeys);
        $this->redis->hset($persitanceName, $persistId, serialize($instance));
    }

    public function delInstance($persistanceName, array $primaryKeys)
    {
        $persistId = self::getPersistanceId($primaryKeys);
        if ($this->redis->hexists($persistanceName, $persistId)) {
            $this->redis->hdel($persistanceName, [
                $persistId
            ]);
        }
    }

    public function getData(array $keys, $persistanceName)
    {
        if (count($keys) > 0) {
            $values = $this->redis->hmget($persistanceName, $keys);
            if ($values !== null) {
                foreach ($values as $key => $value) {
                    if ($value !== null) {
                        $values[$key] = unserialize($value);
                    } else {
                        unset($values[$key]);
                    }
                }
                return $values;
            }
        }
        return [];
    }

    private static function getListKeys($persistanceName, array $parameters)
    {
        $serialParam = serialize($parameters);
        $filterId = md5($serialParam);
        $listId = $persistanceName . "-" . $filterId;
        return [
            $serialParam,
            $filterId,
            $listId,
            $listId . self::BLOCKER_SUFFIX
        ];
    }

    public function getColumns($persistanceName)
    {
        $columnString = $this->redis->get($persistanceName . self::COLUMN_SUFFIX);
        if ($columnString !== null) {
            return $columnString !== "" ? explode(self::LIST_DELIM, $columnString) : [];
        }
        return null;
    }

    public function populateColumns($persistanceName, callable $populator)
    {
        $key = $persistanceName . self::COLUMN_SUFFIX;
        $blockerKey = $key . self::BLOCKER_SUFFIX;
        $setter = function (MultiExec $tx, array $result) use ($key) {
            $tx->set($key, implode(self::LIST_DELIM, $result));
        };
        $result = $this->populateCache($blockerKey, $setter, $populator);
        if ($result === null) {
            return $this->getColumns($persistanceName);
        }
        return $result;
    }

    public function refreshList($persistanceName, array $parameters, callable $refresh)
    {
        list ($serializedParams, $filterId, $listId, $blockerKey) = self::getListKeys($persistanceName, $parameters);
        $this->logger->info(NameUtil::shortClassName(self::class) . ": Refreshing list: " . $listId);
        $setter = function (MultiExec $tx, array $result) use ($listId, $persistanceName, $filterId, $serializedParams) {
            $tx->hset($persistanceName . self::FILTER_SUFFIX, $filterId, $serializedParams);
            $keys = array_map("self::getPersistanceId", $result);
            $tx->set($listId, serialize($keys));
        };
        $this->populateCache($blockerKey, $setter, $refresh);
    }

    public function populateList($persistanceName, array $parameters, callable $populator)
    {
        list ($serializedParams, $filterId, $listId, $blockerKey) = self::getListKeys($persistanceName, $parameters);
        $setter = function (MultiExec $tx, array $result) use ($persistanceName, $listId, $filterId, $serializedParams) {
            $dict = static::parseList($result);
            $tx->hset($persistanceName . self::FILTER_SUFFIX, $filterId, $serializedParams);
            $tx->set($listId, serialize(array_keys($dict)));
            if (count($dict) > 0) {
                $tx->hmset($persistanceName, $dict);
            }
        };
        $result = $this->populateCache($blockerKey, $setter, $populator);
        if ($result !== null) {
            return $result;
        }
        return $this->getList($persistanceName, $parameters);
    }

    public function getList($persistanceName, array $parameters)
    {
        $listKey = self::getListKeys($persistanceName, $parameters)[2];
        $keys = $this->redis->get($listKey);
        return ($keys !== null) ? $this->getData(unserialize($keys), $persistanceName) : null;
    }

    public function listExists($persistanceName, array $parameters)
    {
        $listKey = self::getListKeys($persistanceName, $parameters)[2];
        return $this->exists($listKey);
    }

    public function addToList($persistanceName, array $parameters, array $keyList)
    {
        if (count($keyList)) {
            $params = self::getListKeys($persistanceName, $parameters);
            $listId = $params[2];
            $options = array(
                'cas' => true, // Make the transaction watch for changes
                'watch' => $listId, // Key that needs to be WATCHed to detect changes
                'retry' => 3
            );
            $this->redis->transaction($options,
                function (MultiExec $tx) use ($keyList, $listId) {
                    $listStr = $tx->get($listId);
                    $addArr = array_map('self::getPersistanceId', $keyList);
                    
                    $listArr = ($listStr !== null) ? unserialize($listStr) : [];
                    $listArr = array_merge($listArr, $addArr);
                    $tx->set($listId, serialize($listArr));
                });
        }
    }

    public function setAll($persistanceName, array $map)
    {
        foreach ($map as $key => $value) {
            $map[$key] = serialize($value);
        }
        $this->redis->hmset($persistanceName, $map);
    }

    public function getAll($persistanceName)
    {
        $map = $this->redis->hgetall($persistanceName);
        if ($map !== null && count($map) > 0) {
            foreach ($map as $key => $value) {
                if ($value !== null) {
                    $map[$key] = unserialize($value);
                } else {
                    unset($map[$key]);
                }
            }
        }
        return $map;
    }

    public function setModels($persistanceName, array $models)
    {
        $dict = static::parseList($models);
        $this->redis->hmset($persistanceName, $dict);
    }

    public function getParameters($persistanceName)
    {
        $filters = $this->redis->hgetall($persistanceName . self::FILTER_SUFFIX);
        if ($filters !== null) {
            return array_map('unserialize', $filters);
        }
        return [];
    }

    public static function parseList(array $list)
    {
        $dict = [];
        foreach ($list as $key => $value) {
            $keyValue = ($value instanceof ModelInterface) ? static::getPersistanceId($value->getPrimaryKeyMap()) : $key;
            $dict[$keyValue] = serialize($value);
        }
        return $dict;
    }

    public static function getPersistanceId(array $primaryKeys)
    {
        return implode('-', $primaryKeys);
    }

    public function regularSet($key)
    {
        return new Set($this->redis, $key);
    }

    public function sortedSet($key)
    {
        return new SortedSet($this->redis, $key);
    }
}