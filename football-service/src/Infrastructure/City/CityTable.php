<?php


namespace Sportal\FootballApi\Infrastructure\City;


class CityTable
{
    const TABLE_NAME = "city";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_COUNTRY = "country";
    const FIELD_UPDATED_AT = "updated_at";
    const FIELD_CREATED_AT = "created_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
        ];
    }
}