<?php
namespace Sportal\FootballApi\Database\Query;

class SortDirection
{

    const ASC = 'asc';

    const DESC = 'desc';

    public static function getAll()
    {
        return [
            static::ASC,
            static::DESC
        ];
    }
}

