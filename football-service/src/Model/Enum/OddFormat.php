<?php


namespace Sportal\FootballApi\Model\Enum;


use MyCLabs\Enum\Enum;

/**
 * @method static OddFormat FRACTIONAL()
 * @method static OddFormat DECIMAL()
 * @method static OddFormat MONEYLINE()
 */
class OddFormat extends Enum
{
    const FRACTIONAL = 'fractional';
    const DECIMAL = 'decimal';
    const MONEYLINE = 'moneyline';
}
