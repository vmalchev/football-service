<?php
namespace Sportal\FootballApi\Cache;

use Predis\Client;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Cache\Entity\EntityManager;
use Sportal\FootballApi\Cache\Index\IndexableInterface;
use Sportal\FootballApi\Cache\Index\SortedIndex;
use Sportal\FootballApi\Cache\Lock\SingleInstanceLock;
use Sportal\FootballApi\Cache\Lock\UnlockCommand;
use Sportal\FootballApi\Cache\Serializer\SerializerInterface;
use Sportal\FootballApi\Util\ModelNameUtil;
use Sportal\FootballApi\Cache\Map\Map;

class Cache
{

    const EMPTY_COLLECTION = 'NONE';

    const DELIM = ":";

    const BLOCKER_SUFFIX = "blocker";

    private $client;

    private $logger;

    private $nameUtil;

    private $serializer;

    private $indexManager;

    private $entityManager;

    public function __construct(Client $redis, ModelNameUtil $nameUtil, LoggerInterface $logger,
        SerializerInterface $serializer)
    {
        $this->client = $redis;
        $this->logger = $logger;
        $this->nameUtil = $nameUtil;
        $this->serializer = $serializer;
        $this->indexManager = new CollectionManager($this);
        $this->entityManager = new EntityManager($this);
        $this->client->getProfile()->defineCommand('unlock', UnlockCommand::class);
    }

    public function getSortedIndex($className, $indexName)
    {
        return new SortedIndex($this, $className, $indexName);
    }

    public function generateIndexKey($className, $indexName)
    {
        return $this->indexManager->generateIndexKey($className, $indexName);
    }

    public function generateKey(IndexableInterface $instance)
    {
        return $this->entityManager->generateKey($instance);
    }

    public function createLock($key)
    {
        $mutexKey = $key . static::DELIM . static::BLOCKER_SUFFIX;
        return new SingleInstanceLock($this->client, $mutexKey, $this->logger);
    }

    public function getIndexManager()
    {
        return $this->indexManager;
    }

    public function isEmptyCollection(array $collection)
    {
        return empty($collection) || reset($collection) == static::EMPTY_COLLECTION;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getSerializer()
    {
        return $this->serializer;
    }

    public function getNameUtil()
    {
        return $this->nameUtil;
    }

    public function formatTime(\DateTime $time)
    {
        $time->setTimezone(new \DateTimeZone('UTC'));
        return $time->getTimestamp();
    }

    public function getEntityManager($className)
    {
        return $this->entityManager;
    }

    public function get($key)
    {
        return $this->client->get($key);
    }

    public function set($key, $value)
    {
        $this->client->set($key, $value);
    }

    public function getMap($className, $indexName)
    {
        return new Map($this, $className, $indexName);
    }
}