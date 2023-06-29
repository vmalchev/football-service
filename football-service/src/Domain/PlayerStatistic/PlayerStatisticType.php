<?php
namespace Sportal\FootballApi\Domain\PlayerStatistic;

use MyCLabs\Enum\Enum;


class PlayerStatisticType extends Enum
{
    const GOALS = 'goals';
    const PLAYED = 'played';
    const ASSISTS = 'assists';
    const MINUTES = 'minutes';
    const STARTED = 'started';
    const CONCEDED = 'conceded';
    const RED_CARDS = 'red_cards';
    const CLEANSHEETS = 'cleansheets';
    const YELLOW_CARDS = 'yellow_cards';
    const SUBSTITUTE_IN = 'substitute_in';
    const SUBSTITUTE_OUT = 'substitute_out';
    const GOALS_SUBSTITUTE = 'goals_substitute';
    const MINUTES_SUBSTITUTE = 'minutes_substitute';
    const PENALTY_GOALS = 'penalty_goals';
    const OWN_GOALS = 'own_goals';
    const SHOTS = 'shots';
    const SHOTS_ON_TARGET = 'shots_on_target';
    const OFFSIDES = 'offsides';
    const FOULS_COMMITTED = 'fouls_committed';
    const PENALTIES_COMMITTED = 'penalties_committed';
    const PENALTIES_SAVED = 'penalties_saved';
    const PENALTIES_MISSED = 'penalties_missed';
    const PENALTIES_RECEIVED = 'penalties_received';
    const CAUGHT_BALL = 'caught_ball';
    const SAVES = 'saves';
}