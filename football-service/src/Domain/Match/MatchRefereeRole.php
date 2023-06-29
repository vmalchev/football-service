<?php


namespace Sportal\FootballApi\Domain\Match;


use MyCLabs\Enum\Enum;

/**
 * @method static REFEREE()
 */
class MatchRefereeRole extends Enum
{
    const REFEREE = 'REFEREE';

    public static function forKey(?string $key): ?MatchRefereeRole
    {
        if ($key === null) {
            return null;
        }
        return self::__callStatic($key, []);
    }
}