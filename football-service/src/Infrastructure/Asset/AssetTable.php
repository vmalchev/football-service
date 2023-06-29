<?php


namespace Sportal\FootballApi\Infrastructure\Asset;


class AssetTable
{
    const TABLE_NAME = "asset";

    const FIELD_ID = "id";
    const FIELD_ENTITY = "entity";
    const FIELD_ENTITY_ID = "entity_id";
    const FIELD_TYPE = "type";
    const FIELD_PATH = "path";
    const FIELD_CONTEXT_TYPE = "context_type";
    const FIELD_CONTEXT_ID = "context_id";
    const FIELD_CREATED_AT = "created_at";
    const FIELD_UPDATED_AT = "updated_at";

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_ENTITY,
            self::FIELD_ENTITY_ID,
            self::FIELD_TYPE,
            self::FIELD_PATH,
            self::FIELD_CONTEXT_TYPE,
            self::FIELD_CONTEXT_ID,
            self::FIELD_CREATED_AT,
            self::FIELD_UPDATED_AT,
        ];
    }
}