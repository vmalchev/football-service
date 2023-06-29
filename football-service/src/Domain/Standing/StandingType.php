<?php


namespace Sportal\FootballApi\Domain\Standing;


use MyCLabs\Enum\Enum;

/**
 * @method static StandingType LEAGUE()
 * @method static StandingType TOP_SCORER()
 * @method static StandingType CARD_LIST()
 */
class StandingType extends Enum
{
    const LEAGUE = 'league';
    const TOP_SCORER = 'topscorer';

    public static function forKey($key): StandingType
    {
        return self::__callStatic($key, []);
    }

}