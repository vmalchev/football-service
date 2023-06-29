<?php


namespace Sportal\FootballApi\Domain\TeamSquad;


use MyCLabs\Enum\Enum;

/**
 * @method static TeamSquadStatus ACTIVE()
 * @method static TeamSquadStatus INACTIVE()
 */
class TeamSquadStatus extends Enum
{
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
}


