<?php


namespace Sportal\FootballApi\Domain\Search;


use MyCLabs\Enum\Enum;

class ScopeEntityType extends Enum
{
    private const PLAYER_SCOPE = 'player';
    private const TEAM_SCOPE = 'team';
    private const COACH_SCOPE = 'coach';
    private const VENUE_SCOPE = 'venue';
    private const TOURNAMENT_SCOPE = 'tournament';
    private const PRESIDENT_SCOPE = "president";

    public static function getValues()
    {
        return [
            self::PLAYER_SCOPE,
            self::TEAM_SCOPE,
            self::COACH_SCOPE,
            self::VENUE_SCOPE,
            self::TOURNAMENT_SCOPE,
            self::PRESIDENT_SCOPE
        ];
    }
}