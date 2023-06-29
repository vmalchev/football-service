<?php

namespace Sportal\FootballApi\Domain\Round;

use MyCLabs\Enum\Enum;

/**
 * @method static RoundStatus ACTIVE()
 * @method static RoundStatus INACTIVE()
 */
class RoundStatus extends Enum
{

    const ACTIVE = "ACTIVE";
    const INACTIVE = "INACTIVE";
}