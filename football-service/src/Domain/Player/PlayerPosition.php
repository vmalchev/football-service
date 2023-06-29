<?php


namespace Sportal\FootballApi\Domain\Player;


use MyCLabs\Enum\Enum;

class PlayerPosition extends Enum
{
    const KEEPER = 'keeper';
    const DEFENDER = 'defender';
    const MIDFIELDER = 'midfielder';
    const FORWARD = 'forward';

}