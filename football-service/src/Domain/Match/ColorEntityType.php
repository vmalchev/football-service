<?php


namespace Sportal\FootballApi\Domain\Match;

use MyCLabs\Enum\Enum;

/**
 * @method static ColorEntityType TEAM()
 * @method static ColorEntityType MATCH()
 */
class ColorEntityType extends Enum
{
    const TEAM = "team";
    const MATCH = "match";

    public static function forKey(?string $key): ?ColorEntityType
    {
        if ($key === null) {
            return null;
        }
        return self::__callStatic($key, []);
    }
}