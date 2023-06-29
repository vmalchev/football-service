<?php

namespace Sportal\FootballApi\Domain\Translation;

use MyCLabs\Enum\Enum;

/**
 * @method static TranslationEntity TEAM()
 * @method static TranslationEntity STAGE()
 * @method static TranslationEntity COUNTRY()
 * @method static TranslationEntity PLAYER()
 * @method static TranslationEntity COACH()
 * @method static TranslationEntity LINEUP_PLAYER_TYPE()
 * @method static TranslationEntity MATCH_STATUS()
 * @method static TranslationEntity VENUE()
 * @method static TranslationEntity REFEREE()
 * @method static TranslationEntity CITY()
 * @method static TranslationEntity PRESIDENT()
 * @method static TranslationEntity STANDING_RULE()
 * @method static TranslationEntity TOURNAMENT()
 * @method static TranslationEntity GROUP()
 * @method static TranslationEntity SEASON()
 * @method static TranslationEntity ROUND_TYPE()
 */
class TranslationEntity extends Enum
{
    const LINEUP_PLAYER_TYPE = 'event_player_type';
    const COACH = 'coach';
    const VENUE = 'venue';
    const TOURNAMENT = 'tournament';
    const TEAM = 'team';
    const COUNTRY = 'country';
    const MATCH_STATUS = 'event_status';
    const STAGE = 'tournament_season_stage';
    const PLAYER = 'player';
    const REFEREE = 'referee';
    const CITY = 'city';
    const PRESIDENT = 'president';
    const STANDING_RULE = 'standing_rule';
    const GROUP = 'stage_group';
    const SEASON = 'tournament_season';
    const ROUND_TYPE = 'round_type';
}