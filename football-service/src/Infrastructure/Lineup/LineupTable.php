<?php


namespace Sportal\FootballApi\Infrastructure\Lineup;


class LineupTable
{
    const TABLE_NAME = "lineup";

    const FIELD_MATCH_ID = "event_id";
    const FIELD_CONFIRMED = "confirmed";
    const FIELD_HOME_FORMATION = "home_formation";
    const FIELD_AWAY_FORMATION = "away_formation";
    const FIELD_HOME_COACH_ID = "home_coach_id";
    const FIELD_HOME_COACH = "home_coach";
    const FIELD_AWAY_COACH_ID = "away_coach_id";
    const FIELD_AWAY_COACH = "away_coach";
    const FIELD_HOME_COACH_NAME = "home_coach_name";
    const FIELD_AWAY_COACH_NAME = "away_coach_name";
    const FIELD_UPDATED_AT = "updated_at";
    const FIELD_HOME_TEAM_ID = "home_id";
    const FIELD_AWAY_TEAM_ID = "away_id";
    const FIELD_MATCH_PLAYERS = "event_player";
    const FIELD_HOME_PLAYERS = "event_player";
    const FIELD_AWAY_PLAYERS = "event_player";

    public static function getColumns(): array
    {
        return [
            self::FIELD_MATCH_ID,
            self::FIELD_CONFIRMED,
            self::FIELD_HOME_FORMATION,
            self::FIELD_AWAY_FORMATION,
            self::FIELD_HOME_COACH_ID,
            self::FIELD_AWAY_COACH_ID,
        ];
    }
}