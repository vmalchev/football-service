<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


class PlayerStatisticTable
{
    const TABLE_NAME = "match_player_statistics";

    const FIELD_MATCH_ID = "match_id";
    const FIELD_MATCH_ENTITY = "match";

    const FIELD_PLAYER_ID = "player_id";
    const FIELD_PLAYER_ENTITY = "player";

    const FIELD_TEAM_ID = "team_id";
    const FIELD_TEAM_ENTITY = "team";

    const FIELD_PLAYER_STATISTIC_ITEM = "statistics";
    const FIELD_ORIGIN = "origin";

    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_MATCH_ID,
            self::FIELD_MATCH_ENTITY,
            self::FIELD_PLAYER_ID,
            self::FIELD_PLAYER_ENTITY,
            self::FIELD_TEAM_ID,
            self::FIELD_TEAM_ENTITY,
            self::FIELD_PLAYER_STATISTIC_ITEM,
            self::FIELD_ORIGIN,
        ];
    }
}