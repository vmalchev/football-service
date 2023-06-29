<?php

namespace Sportal\FootballApi\Domain\Stage;

use MyCLabs\Enum\Enum;


/**
 * @method static StageStatus ACTIVE()
 * @method static StageStatus INACTIVE()
 */
class StageStatus extends Enum
{
    const ACTIVE = "ACTIVE";
    const INACTIVE = "INACTIVE";
}