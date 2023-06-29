<?php


namespace Sportal\FootballApi\Domain\Tournament;


use MyCLabs\Enum\Enum;

/**
 * @method static TournamentRegion DOMESTIC()
 * @method static TournamentRegion INTERNATIONAL()
 */
class TournamentRegion extends Enum
{
    const DOMESTIC = 'DOMESTIC';
    const INTERNATIONAL = 'INTERNATIONAL';
}


