<?php

namespace Sportal\FootballApi\Domain\Round;

use MyCLabs\Enum\Enum;

/**
 * @method static RoundType KNOCK_OUT()
 * @method static RoundType LEAGUE()
 */
class RoundType extends Enum
{

    const KNOCK_OUT = 'KNOCK_OUT';
    const LEAGUE = 'LEAGUE';

}