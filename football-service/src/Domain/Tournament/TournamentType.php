<?php


namespace Sportal\FootballApi\Domain\Tournament;


use MyCLabs\Enum\Enum;

/**
 * @method static TournamentType CUP()
 * @method static TournamentType LEAGUE()
 */
class TournamentType extends Enum
{
    const CUP = 'CUP';
    const LEAGUE = 'LEAGUE';
}


