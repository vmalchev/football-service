<?php
namespace Sportal\FootballApi\Cache;

use Predis\Client;
use Predis\Transaction\MultiExec;

class SortedSet
{

    protected $cache;

    protected $name;

    public function __construct(Client $cache, $name)
    {
        $this->cache = $cache;
        $this->name = $name;
    }

    public function replace(array $membersScoresDict)
    {
        $this->setAll($membersScoresDict);
    }

    public function addAll(array $membersAndScores)
    {
        if (! empty($membersAndScores)) {
            $this->cache->zadd($this->name, $membersAndScores);
        }
    }

    public function add($member, $score)
    {
        $this->cache->zadd($this->name, [
            $member => $score
        ]);
    }

    public function getScore($member)
    {
        return $this->cache->zscore($this->name, $member);
    }

    public function exists()
    {
        return (boolean) $this->cache->exists($this->name);
    }

    public function getAll()
    {
        if ($this->cache->exists($this->name)) {
            return $this->cache->zrange($this->name, 0, - 1);
        }
        return null;
    }

    protected function setAll(array $dict)
    {
        $this->cache->transaction(
            function (MultiExec $tx) use ($dict) {
                $tx->del($this->name);
                if (! empty($dict)) {
                    $tx->zadd($this->name, $dict);
                }
            });
    }
}