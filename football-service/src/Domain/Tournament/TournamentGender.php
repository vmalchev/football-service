<?php


namespace Sportal\FootballApi\Domain\Tournament;


use MyCLabs\Enum\Enum;

/**
 * @method static TournamentGender MALE()
 * @method static TournamentGender FEMALE()
 */
class TournamentGender extends Enum
{
    const MALE = 'MALE';
    const FEMALE = 'FEMALE';
}


