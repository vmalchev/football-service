<?php
namespace Sportal\FootballApi\Cache;

use Predis\Client;

class Set
{

    protected $cache;

    protected $key;

    public function __construct(Client $cache, $key)
    {
        $this->cache = $cache;
        $this->key = $key;
    }

    public function isMember($member)
    {
        return (boolean) $this->cache->sismember($this->key, $member);
    }
}