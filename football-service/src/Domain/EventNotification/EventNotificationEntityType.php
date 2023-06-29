<?php


namespace Sportal\FootballApi\Domain\EventNotification;


use MyCLabs\Enum\Enum;

/**
 * @method static EventNotificationEntityType TEAM()
 * @method static EventNotificationEntityType PLAYER()
 * @method static EventNotificationEntityType COACH()
 * @method static EventNotificationEntityType VENUE()
 * @method static EventNotificationEntityType COUNTRY()
 * @method static EventNotificationEntityType CITY()
 * @method static EventNotificationEntityType REFEREE()
 * @method static EventNotificationEntityType PRESIDENT()
 * @method static EventNotificationEntityType TOURNAMENT()
 * @method static EventNotificationEntityType SEASON()
 * @method static EventNotificationEntityType STAGE()
 * @method static EventNotificationEntityType GROUP()
 * @method static EventNotificationEntityType ODD_PROVIDER()
 * @method static EventNotificationEntityType MATCH()
 * @method static EventNotificationEntityType MATCH_EVENT()
 * @method static EventNotificationEntityType MATCH_LINEUP()
 */
class EventNotificationEntityType extends Enum
{
    const TEAM = 'team';
    const PLAYER = 'player';
    const COACH = 'coach';
    const VENUE = 'venue';
    const COUNTRY = 'country';
    const CITY = 'city';
    const REFEREE = 'referee';
    const PRESIDENT = 'president';
    const TOURNAMENT = 'tournament';
    const SEASON = 'season';
    const STAGE = 'stage';
    const GROUP = 'group';
    const ODD_PROVIDER = 'odd_provider';
    const MATCH = 'match';
    const MATCH_EVENT = 'match_event';
    const MATCH_LINEUP = 'match_lineup';
}
