<?php

namespace Sportal\FootballApi\Infrastructure\Player;

class PlayerTable
{
    const TABLE_NAME = "player";
    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_COUNTRY = "country";
    const FIELD_BIRTHDATE = "birthdate";
    const FIELD_BIRTH_CITY_ID = "city_id";
    const FIELD_BIRTH_CITY = "city";
    const FIELD_PROFILE = "profile";
    const FIELD_SOCIAL = "social";
    const FIELD_ACTIVE = "active";
    const FIELD_POSITION = "position";
    const FIELD_GENDER = 'gender';

    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    public static function getColumns()
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
            self::FIELD_BIRTHDATE,
            self::FIELD_BIRTH_CITY_ID,
            self::FIELD_PROFILE,
            self::FIELD_SOCIAL,
            self::FIELD_ACTIVE,
            self::FIELD_POSITION,
            self::FIELD_GENDER
        ];
    }
}
