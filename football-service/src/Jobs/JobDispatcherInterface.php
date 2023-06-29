<?php
namespace Sportal\FootballApi\Jobs;

interface JobDispatcherInterface
{

    /**
     * Check if the job dispatcher supports the following job.
     * @param string $name
     * @return bool
     */
    public function supports($name);

    /**
     *
     * Dispatch a job with the following arguments.
     * @param string $name
     * @param array $args
     */
    public function dispatch($name, array $args);
}