<?php


namespace Sportal\FootballApi\Infrastructure\President;


class PresidentTable
{
    const TABLE_NAME = "president";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_UPDATED_AT = "updated_at";
    const FIELD_CREATED_AT = "created_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
        ];
    }
}