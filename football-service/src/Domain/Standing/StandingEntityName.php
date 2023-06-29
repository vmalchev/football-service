<?php


namespace Sportal\FootballApi\Domain\Standing;


use MyCLabs\Enum\Enum;

/**
 * @method static StandingEntityName GROUP()
 * @method static StandingEntityName STAGE()
 * @method static StandingEntityName SEASON()
 */
class StandingEntityName extends Enum
{
    const SEASON = 'tournament_season';
    const STAGE = 'tournament_season_stage';
    const GROUP = 'stage_group';

    public static function forKey($key): StandingEntityName
    {
        return self::__callStatic($key, []);
    }
}