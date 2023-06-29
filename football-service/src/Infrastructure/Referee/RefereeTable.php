<?php


namespace Sportal\FootballApi\Infrastructure\Referee;


class RefereeTable
{
    const TABLE_NAME = "referee";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_COUNTRY = "country";
    const FIELD_BIRTHDATE = "birthdate";
    const FIELD_ACTIVE = "active";
    const FIELD_GENDER = 'gender';

    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
            self::FIELD_BIRTHDATE,
            self::FIELD_ACTIVE,
            self::FIELD_GENDER
        ];
    }

}