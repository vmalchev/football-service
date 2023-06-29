<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


use MyCLabs\Enum\Enum;

/**
 * @method static OriginType MANUAL()
 * @method static OriginType AUTOMATIC_CALCULATION()
 * @method static OriginType PRIMARY_PROVIDER()
 */
class OriginType extends Enum
{
    const MANUAL = 'manual';
    const AUTOMATIC_CALCULATION = 'automatic_calculation';
    const PRIMARY_PROVIDER = 'primary_provider';

    public static function forKey(?string $key): ?OriginType
    {
        if ($key === null) {
            return null;
        }

        return self::__callStatic($key, []);
    }
}