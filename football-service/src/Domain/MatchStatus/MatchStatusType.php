<?php


namespace Sportal\FootballApi\Domain\MatchStatus;


use MyCLabs\Enum\Enum;

/**
 * @method static MatchStatusType FINISHED()
 * @method static MatchStatusType NOT_STARTED()
 * @method static MatchStatusType LIVE()
 * @method static MatchStatusType INTERRUPTED()
 * @method static MatchStatusType CANCELLED()
 * @method static MatchStatusType UNKNOWN()
 */
class MatchStatusType extends Enum
{
    const FINISHED = 'finished';
    const NOT_STARTED = 'notstarted';
    const LIVE = 'inprogress';
    const INTERRUPTED = 'interrupted';
    const CANCELLED = 'cancelled';
    const UNKNOWN = 'unknown';
}