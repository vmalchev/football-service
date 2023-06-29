<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use MyCLabs\Enum\Enum;

/**
 * @method static TeamPositionStatus HOME()
 * @method static TeamPositionStatus AWAY()
 */
class TeamPositionStatus extends Enum
{
    const HOME = 'HOME';
    const AWAY = 'AWAY';

    public static function forKey(string $key): TeamPositionStatus
    {
        return self::__callStatic($key, []);
    }

}