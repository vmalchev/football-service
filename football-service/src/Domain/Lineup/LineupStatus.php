<?php


namespace Sportal\FootballApi\Domain\Lineup;

use MyCLabs\Enum\Enum;

class LineupStatus extends Enum
{
    const CONFIRMED = true;
    const UNCONFIRMED = false;

    public static function forKey(string $key): LineupStatus
    {
        return self::__callStatic($key, []);
    }
}