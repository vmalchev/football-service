<?php
namespace Sportal\FootballApi\Cache\Entity;

use Predis\Transaction\MultiExec;

class EntityUpdateQueue
{

    private $updates;

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->updates = [];
    }

    public function add(CacheEntityInterface $instance)
    {
        $key = $this->em->generateKey($instance);
        $this->updates[$key] = $this->em->encode($instance);
        return $key;
    }

    public function flush(MultiExec $tx)
    {
        $this->em->setAll($tx, $this->updates);
    }
}