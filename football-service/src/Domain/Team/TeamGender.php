<?php

namespace Sportal\FootballApi\Domain\Team;

use MyCLabs\Enum\Enum;

/**
 * @method static TeamGender MALE()
 * @method static TeamGender FEMALE()
 */
class TeamGender extends Enum
{

    const MALE = 'MALE';
    const FEMALE = 'FEMALE';

    public static function forKey($key)
    {
        return self::__callStatic($key, []);
    }
}