<?php


namespace Sportal\FootballApi\Domain\Search;


use MyCLabs\Enum\Enum;

class EntityType extends Enum
{
    private const PLAYER = 'player';
    private const TEAM = 'team';
    private const COACH = 'coach';
    private const VENUE = 'venue';
    private const REFEREE = 'referee';
    private const TOURNAMENT = 'tournament';
    private const CITY = 'city';
    private const PRESIDENT = 'president';
    private const COUNTRY = 'country';

    public static function getValues()
    {
        return [
            self::PLAYER,
            self::TEAM,
            self::COACH,
            self::VENUE,
            self::REFEREE,
            self::TOURNAMENT,
            self::CITY,
            self::PRESIDENT,
            self::COUNTRY,
        ];
    }

}