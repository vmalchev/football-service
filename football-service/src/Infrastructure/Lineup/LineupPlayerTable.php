<?php


namespace Sportal\FootballApi\Infrastructure\Lineup;


class LineupPlayerTable
{
    const TABLE_NAME = "event_player";

    const FIELD_ID = "id";
    const FIELD_MATCH_ID = "event_id";
    const FIELD_PLAYER_NAME = "player_name";
    const FIELD_TYPE_ID = "event_player_type_id";
    const FIELD_TYPE = "event_player_type";
    const FIELD_PLAYER_ID = "player_id";
    const FIELD_PLAYER = "player";
    const FIELD_POSITION_X = "position_x";
    const FIELD_POSITION_Y = "position_y";
    const FIELD_SHIRT_NUMBER = "shirt_number";
    const FIELD_HOME_TEAM = "home_team";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_MATCH_ID,
            self::FIELD_PLAYER_NAME,
            self::FIELD_TYPE_ID,
            self::FIELD_PLAYER_ID,
            self::FIELD_POSITION_X,
            self::FIELD_POSITION_Y,
            self::FIELD_SHIRT_NUMBER,
        ];
    }
}