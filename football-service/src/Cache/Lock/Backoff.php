<?php
namespace Sportal\FootballApi\Cache\Lock;

class Backoff
{

    /**
     * This is the base of the exponential back off: each loop sleep is STEP^n + random
     */
    const STEP = 3.3;

    const MIN_EXTRA_WAIT = 30;

    const MAX_EXTRA_WAIT = 200;

    private $count;

    private $totalDelay;

    private $maxWait;

    private $message;

    /**
     *
     * @param integer $maxWait Maximum number to wait for
     */
    public function __construct($maxWait, $timeoutMessage)
    {
        $this->maxWait = $maxWait;
        $this->message = $timeoutMessage;
    }

    public function reset()
    {
        $this->count = 1;
        $this->totalDelay = 0;
    }

    public function once()
    {
        if ($this->totalDelay > $this->maxWait) {
            throw new BackoffTimeoutException($this->message);
        }
        $delay = $this->getDelay($this->count);
        $this->totalDelay += $delay;
        $this->count ++;
        usleep($delay * 1000);
    }

    private function getDelay($count)
    {
        return floor(pow(static::STEP, $count) + mt_rand(static::MIN_EXTRA_WAIT, static::MAX_EXTRA_WAIT));
    }
}