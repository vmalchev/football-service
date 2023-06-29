<?php


namespace Sportal\FootballApi\Dto\Odd;


use MyCLabs\Enum\Enum;


/**
 * @method static OddsType PREMATCH()
 * @method static OddsType LIVE()
 * @method static OddsType ALL()
 */
class OddsType extends Enum
{
    const PREMATCH = 'prematch';
    const LIVE = 'live';
    const ALL = 'all';
}