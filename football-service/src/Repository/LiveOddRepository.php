<?php
namespace Sportal\FootballApi\Repository;

use Alexsabdev\Odds\DecimalOdd;
use Predis\Client;
use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Model\Enum\OddFormat;
use Sportal\FootballApi\Model\Odd;

class LiveOddRepository
{

    const EVENT_PREFIX = 'liveodd:event';

    const MAP_PREFIX = 'liveodd';
    
    const MEMBER_SET = "liveodd:members:set";

    protected $cache;

    protected $oddLinks;

    public function __construct(Client $cache, OddLinkRepository $oddLinks)
    {
        $this->cache = $cache;
        $this->oddLinks = $oddLinks;
    }

    public function createObject(array $oddArr)
    {
        $odd = new Odd();
        $odd->setEventId($oddArr['event_id'])
            ->setOddProvider($oddArr['odd_provider'])
            ->setSource($oddArr['source']);
        if (isset($oddArr['reference'])) {
            $odd->setReference($oddArr['reference']);
        }
        $odd->setData($oddArr['data']);
        return $odd;
    }

    public function findAll($clientCode = null)
    {
        if (empty($clientCode)) {
            return $this->getAll(null);
        } else {
            $key = static::MAP_PREFIX . ":" . $clientCode;
            return $this->findSorted($key, $clientCode);
        }
    }

    public function findByEvent($eventId, $clientCode = null)
    {
        $key = static::EVENT_PREFIX . ":" . $eventId;
        if (empty($clientCode)) {
            $keys = $this->cache->smembers($key);
            if (! empty($keys)) {
                return $this->getAll($keys);
            }
        } else {
            return $this->findSorted($key . ":" . $clientCode, $clientCode);
        }
        
        return [];
    }

    /**
     *
     * @param Odd[] $odds
     * @param Odd[] $eventIndex
     */
    public function flush(array $odds)
    {
        $oddLinks = $this->oddLinks->getProviderIndex();
        $sortedSets = [];
        $eventSets = [];
        $map = [];
        foreach ($odds as $odd) {
            $key = static::getKeyName($odd);
            $map[$key] = serialize($odd);
            $eventKey = static::EVENT_PREFIX . ":" . $odd->getEventId();
            if (! isset($eventSets[$eventKey])) {
                $eventSets[$eventKey] = [];
            }
            $eventSets[$eventKey][] = $key;
            $providerId = $odd->getOddProvider()->getId();
            $links = isset($oddLinks[$providerId]) ? $oddLinks[$providerId] : [];
            foreach ($links as $link) {
                $clientCode = $link->getOddClient()->getCode();
                $sortedSets[$eventKey . ':' . $clientCode][$key] = $link->getSortorder();
                $sortedSets[static::MAP_PREFIX . ":" . $clientCode][$key] = $link->getSortorder();
            }
        }
        
        $currentKeys = $this->cache->smembers(static::MEMBER_SET);
        
        $this->cache->transaction(
            function (MultiExec $tx) use ($map, $eventSets, $sortedSets, $currentKeys) {
                
                if (! empty($currentKeys)) {
                    $tx->del($currentKeys);
                }
                
                if (! empty($map)) {
                    $tx->hmset(static::MAP_PREFIX, $map);
                }
                
                foreach ($eventSets as $key => $members) {
                    $tx->sadd($key, $members);
                }
                
                foreach ($sortedSets as $key => $members) {
                    $tx->zadd($key, $members);
                }
                
                $allKeys = array_merge([static::MAP_PREFIX], array_keys($eventSets), array_keys($sortedSets));
                $tx->del(static::MEMBER_SET);
                $tx->sadd(static::MEMBER_SET, $allKeys);
            });
    }

    public static function getKeyName(Odd $model)
    {
        return implode('-', $model->getPrimaryKeyMap());
    }

    /**
     * @param array $oddProviderIds
     * @param array $eventIds
     * @param null $oddFormat
     * @return Odd[]
     */
    public function findByKeys(array $oddProviderIds, array $eventIds, $oddFormat=null) {
        $keys = [];
        foreach ($eventIds as $eventId) {
            foreach ($oddProviderIds as $oddProviderId) {
                $keys[] = $eventId . '-' . $oddProviderId;
            }
        }

        return $this->getAll($keys, $oddFormat);
    }

    protected function getAll($keys, $oddFormat=null)
    {
        $rawData = (empty($keys)) ? $this->cache->hgetall(static::MAP_PREFIX) : $this->cache->hmget(static::MAP_PREFIX, $keys);
        
        if (! empty($rawData)) {
            $objectInstances = [];
            foreach ($rawData as $string) {
                if ($string !== null && ($instance = unserialize($string)) !== null) {
                    if ($oddFormat !== null && $oddFormat->getValue() !== OddFormat::DECIMAL()->getValue()) {
                        $oddsData = $instance->getData();
                        foreach ($oddsData as &$oddData) {
                            foreach ($oddData as &$odd) {
                                $oddsConverter = new DecimalOdd($odd['odds']);
                                if ($oddFormat->getValue() === OddFormat::FRACTIONAL()->getValue()) {
                                    $odd['odds'] = $oddsConverter->toFractional()->value();
                                } elseif ($oddFormat->getValue() === OddFormat::MONEYLINE()->getValue()) {
                                    $odd['odds'] = $oddsConverter->toMoneyline()->value();
                                }
                            }
                        }
                        $instance->setData($oddsData);
                    }

                    $objectInstances[] = $instance;
                }
            }
            
            return $objectInstances;
        }
        
        return [];
    }

    protected function findSorted($key, $clientCode = null)
    {
        $keys = $this->cache->zrange($key, 0, - 1);
        if (! empty($keys)) {
            $odds = $this->getAll($keys);
            if (! empty($clientCode)) {
                $links = $this->oddLinks->findByClient($clientCode);
                foreach ($links as $oddLink) {
                    foreach ($odds as $odd) {
                        if ($odd->getOddProvider()->getId() == $oddLink->getOddProviderId()) {
                            $odd->setLinks($oddLink->getLinks());
                        }
                    }
                }
            }
            return $odds;
        }
        
        return [];
    }
}
