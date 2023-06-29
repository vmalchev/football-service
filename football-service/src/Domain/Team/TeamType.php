<?php

namespace Sportal\FootballApi\Domain\Team;

use MyCLabs\Enum\Enum;

/**
 * @method static TeamType PLACEHOLDER()
 * @method static TeamType NATIONAL()
 * @method static TeamType CLUB()
 */
class TeamType extends Enum
{
    const PLACEHOLDER = 'placeholder';
    const NATIONAL = 'national';
    const CLUB = 'club';

    public static function forKey($key)
    {
        return self::__callStatic($key, []);
    }
}
