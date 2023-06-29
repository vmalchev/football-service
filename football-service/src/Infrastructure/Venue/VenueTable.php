<?php


namespace Sportal\FootballApi\Infrastructure\Venue;


class VenueTable
{
    const TABLE_NAME = "venue";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_COUNTRY = "country";
    const FIELD_CITY_ID = "city_id";
    const FIELD_CITY = "city";
    const FIELD_PROFILE = "profile";

    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
            self::FIELD_CITY_ID,
            self::FIELD_PROFILE,
        ];
    }
}