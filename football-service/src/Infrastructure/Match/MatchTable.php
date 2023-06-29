<?php


namespace Sportal\FootballApi\Infrastructure\Match;


class MatchTable
{
    const TABLE_NAME = "event";

    const FIELD_ID = "id";
    const FIELD_HOME_TEAM_ID = "home_id";
    const FIELD_AWAY_TEAM_ID = "away_id";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_HOME_TEAM_ID,
            self::FIELD_AWAY_TEAM_ID,
        ];
    }

}