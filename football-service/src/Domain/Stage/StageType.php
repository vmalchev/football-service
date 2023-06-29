<?php


namespace Sportal\FootballApi\Domain\Stage;


use MyCLabs\Enum\Enum;

/**
 * @method static StageType GROUP()
 * @method static StageType LEAGUE()
 * @method static StageType KNOCK_OUT()
 */
class StageType extends Enum
{
    const GROUP = 'GROUP';
    const LEAGUE = 'LEAGUE';
    const KNOCK_OUT = 'KNOCK_OUT';
}