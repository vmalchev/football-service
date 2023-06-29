<?php

namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Infrastructure\Blacklist\BlacklistRepository;

final class BlacklistRepositoryFactory
{
    private static $instance = null;

    /**
     * @param BlacklistRepository $blacklistRepository
     */
    public static function setInstance(BlacklistRepository $blacklistRepository)
    {
        static::$instance = $blacklistRepository;
    }

    /**
     * Return instance of BlacklistRepository
     * @return BlacklistRepository
     */
    public static function getInstance()
    {
        return static::$instance;
    }
}