<?php


namespace Sportal\FootballApi\Infrastructure\Database\Relation;


use MyCLabs\Enum\Enum;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;

/**
 * @method static RelationType REQUIRED()
 * @method static RelationType OPTIONAL()
 */
class RelationType extends Enum
{
    const REQUIRED = Join::TYPE_INNER;
    const OPTIONAL = Join::TYPE_LEFT;
}