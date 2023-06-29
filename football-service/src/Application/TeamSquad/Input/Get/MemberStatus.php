<?php


namespace Sportal\FootballApi\Application\TeamSquad\Input\Get;


use MyCLabs\Enum\Enum;


/**
 * @method static MemberStatus ACTIVE()
 * @method static MemberStatus INACTIVE()
 * @method static MemberStatus ALL()
 */
class MemberStatus extends Enum
{
    const ALL = 'ALL';
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
}