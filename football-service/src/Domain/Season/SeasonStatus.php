<?php


namespace Sportal\FootballApi\Domain\Season;


use MyCLabs\Enum\Enum;
/**
 * @method static SeasonStatus ACTIVE()
 * @method static SeasonStatus INACTIVE()
 */
class SeasonStatus extends Enum
{
    const ACTIVE = 'ACTIVE';
    const INACTIVE = 'INACTIVE';
}