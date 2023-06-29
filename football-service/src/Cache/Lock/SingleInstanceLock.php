<?php
namespace Sportal\FootballApi\Cache\Lock;

use Predis\Client;
use Predis\PredisException;
use Psr\Log\LoggerInterface;

class SingleInstanceLock
{

    private $mutexKey;

    private $client;

    private $timeout;

    private $logger;

    public function __construct(Client $client, $mutexKey, LoggerInterface $logger, $timeout = 15)
    {
        $this->client = $client;
        $this->mutexKey = $mutexKey;
        $this->logger = $logger;
        $this->timeout = $timeout;
    }

    /**
     * Executes a block of code exclusively.
     *
     *
     * The code block may throw an exception. In this case the lock will be
     * released as well.
     *
     * @param callable $block The synchronized execution block.
     * @return mixed The return value of the execution block.
     *
     * @throws \Exception The execution block threw an exception.
     * @throws LockAcquireException The mutex could not be aquired, no further side effects.
     */
    public function executeOnce(callable $block, callable $getResult = null)
    {
        $token = $this->acquire();
        if ($token !== null) {
            try {
                return $block();
            } finally {
                $this->client->unlock($this->mutexKey, $token);
            }
        } else {
            $this->waitUnlock();
            if ($getResult !== null) {
                return $getResult();
            }
        }
    }

    public function waitUnlock()
    {
        $backoff = new Backoff($this->timeout * 1000, 'Timeout on waiting for ' . $this->mutexKey . ' to be released');
        $tries = 1;
        do {
            $this->logger->info('Waiting for ' . $this->mutexKey . ' to be released: ' . $tries);
            $backoff->once();
            $tries ++;
        } while ($this->client->exists($this->mutexKey));
    }

    /**
     *
     * @throws LockAcquireException
     * @return NULL|string
     */
    private function acquire()
    {
        $token = uniqid();
        try {
            $locked = $this->client->set($this->mutexKey, $token, "EX", $this->timeout, "NX");
        } catch (PredisException $e) {
            $message = "Could not aquire lock for " . $this->mutexKey;
            throw new LockAcquireException($message, 0, $e);
        }
        return ($locked) ? $token : null;
    }
}