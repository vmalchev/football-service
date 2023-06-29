<?php


namespace Sportal\FootballApi\Domain\Match;


use MyCLabs\Enum\Enum;

/**
 * @method static MatchCoverage LIVE()
 * @method static MatchCoverage NOT_LIVE()
 * @method static UNKNOWN()
 */
class MatchCoverage extends Enum
{
    const LIVE = 'LIVE';
    const NOT_LIVE = 'NOT_LIVE';
    const UNKNOWN = 'UNKNOWN';

    public static function forKey(?string $key): ?MatchCoverage
    {
        if ($key === null) {
            return null;
        }
        return self::__callStatic($key, []);
    }

}