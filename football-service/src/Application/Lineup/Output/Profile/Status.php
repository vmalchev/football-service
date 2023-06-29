<?php


namespace Sportal\FootballApi\Application\Lineup\Output\Profile;


use MyCLabs\Enum\Enum;

/**
 * @method static Status CONFIRMED()
 * @method static Status UNCONFIRMED()
 * @method static Status NOT_AVAILABLE()
 */
class Status extends Enum
{
    const CONFIRMED = 'CONFIRMED';
    const UNCONFIRMED = 'UNCONFIRMED';
    const NOT_AVAILABLE = 'NOT_AVAILABLE';
}