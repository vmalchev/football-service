<?php


namespace Sportal\FootballApi\Infrastructure\Team;


class TeamColorsTable
{
    const TABLE_NAME = "team_colors";

    const FIELD_ENTITY_ID = "entity_id";
    const FIELD_ENTITY_TYPE = "entity_type";
    const FIELD_COLORS = "colors";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ENTITY_ID,
            self::FIELD_ENTITY_TYPE,
            self::FIELD_COLORS
        ];
    }
}