<?php


namespace Sportal\FootballApi\Domain\MatchEvent;


use MyCLabs\Enum\Enum;

/**
 * @method static MatchEventType YELLOW_RED_CARD()
 * @method static MatchEventType PENALTY_SHOOTOUT_MISSED()
 * @method static MatchEventType PENALTY_SHOOTOUT_SCORED()
 * @method static MatchEventType SUBSTITUTION()
 * @method static MatchEventType GOAL()
 * @method static MatchEventType RED_CARD()
 * @method static MatchEventType PENALTY_MISS()
 * @method static MatchEventType YELLOW_CARD()
 * @method static MatchEventType ASSIST()
 * @method static MatchEventType PENALTY_GOAL()
 * @method static MatchEventType OWN_GOAL()
 */
class MatchEventType extends Enum
{
    const YELLOW_RED_CARD = 'yellow_card_red';
    const PENALTY_SHOOTOUT_MISSED = 'penalty_shootout_missed';
    const PENALTY_SHOOTOUT_SCORED = 'penalty_shootout_scored';
    const SUBSTITUTION = 'substitution';
    const GOAL = 'goal';
    const RED_CARD = 'red_card';
    const PENALTY_MISS = 'penalty_miss';
    const YELLOW_CARD = 'yellow_card';
    const ASSIST = 'assist';
    const PENALTY_GOAL = 'penalty_goal';
    const OWN_GOAL = 'own_goal';

    public static function forKey(string $key): MatchEventType
    {
        return self::__callStatic($key, []);
    }

    public static function isGoal(MatchEventType $type): bool
    {
        $goalTypes = [MatchEventType::GOAL()->getKey(), MatchEventType::OWN_GOAL()->getKey(), MatchEventType::PENALTY_GOAL()->getKey()];
        return in_array($type->getKey(), $goalTypes);
    }

}