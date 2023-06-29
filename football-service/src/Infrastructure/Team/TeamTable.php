<?php


namespace Sportal\FootballApi\Infrastructure\Team;


class TeamTable
{
    const TABLE_NAME = "team";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_THREE_LETTER_CODE = "three_letter_code";
    const FIELD_GENDER = "gender";
    const FIELD_SHORT_NAME = "short_name";
    const FIELD_NATIONAL = "national";
    const FIELD_UNDECIDED = "undecided";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_COUNTRY = "country";
    const FIELD_VENUE_ID = "venue_id";
    const FIELD_VENUE = "venue";
    const FIELD_PRESIDENT_ID = "president_id";
    const FIELD_PRESIDENT = "president";
    const FIELD_SOCIAL = "social";
    const FIELD_PROFILE = "profile";

    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_THREE_LETTER_CODE,
            self::FIELD_GENDER,
            self::FIELD_SHORT_NAME,
            self::FIELD_NATIONAL,
            self::FIELD_UNDECIDED,
            self::FIELD_COUNTRY_ID,
            self::FIELD_VENUE_ID,
            self::FIELD_PRESIDENT_ID,
            self::FIELD_SOCIAL,
            self::FIELD_PROFILE,
        ];
    }
}