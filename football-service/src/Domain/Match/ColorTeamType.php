<?php


namespace Sportal\FootballApi\Domain\Match;


use MyCLabs\Enum\Enum;

/**
 * @method static ColorTeamType HOME()
 * @method static ColorTeamType AWAY()
 */
class ColorTeamType extends Enum
{
    const HOME = "home";
    const AWAY = "away";

    public static function forKey(?string $key): ?ColorTeamType
    {
        if ($key === null) {
            return null;
        }
        return self::__callStatic($key, []);
    }
}