<?php


namespace Sportal\FootballApi\Domain\Blacklist;


use MyCLabs\Enum\Enum;


/**
 * @method static BlacklistType TRANSLATION()
 * @method static BlacklistType ASSET()
 * @method static BlacklistType RELATION()
 * @method static BlacklistType ENTITY()
 */
class BlacklistType extends Enum
{
    const TRANSLATION = 'translation';
    const ASSET = 'asset';
    const RELATION = 'relation';
    const ENTITY = 'entity';

}