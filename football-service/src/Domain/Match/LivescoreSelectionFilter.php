<?php

namespace Sportal\FootballApi\Domain\Match;

use MyCLabs\Enum\Enum;

/**
 * @method static LivescoreSelectionFilter ENABLED()
 * @method static LivescoreSelectionFilter DISABLED()
 */
class LivescoreSelectionFilter extends Enum
{

    const ENABLED = 'ENABLED';
    const DISABLED = 'DISABLED';

}