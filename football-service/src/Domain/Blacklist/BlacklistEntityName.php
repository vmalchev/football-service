<?php


namespace Sportal\FootballApi\Domain\Blacklist;


use MyCLabs\Enum\Enum;

/**
 * @method static BlacklistEntityName TEAM()
 * @method static BlacklistEntityName STAGE()
 * @method static BlacklistEntityName COUNTRY()
 * @method static BlacklistEntityName PLAYER()
 * @method static BlacklistEntityName COACH()
 * @method static BlacklistEntityName LINEUP_PLAYER_TYPE()
 * @method static BlacklistEntityName MATCH_STATUS()
 * @method static BlacklistEntityName VENUE()
 * @method static BlacklistEntityName REFEREE()
 * @method static BlacklistEntityName CITY()
 * @method static BlacklistEntityName PRESIDENT()
 * @method static BlacklistEntityName LINEUP()
 * @method static BlacklistEntityName MATCH()
 * @method static BlacklistEntityName ASSET()
 * @method static BlacklistEntityName SEASON()
 * @method static BlacklistEntityName GROUP()
 */
class BlacklistEntityName extends Enum
{
    const TEAM = "team";
    const TOURNAMENT = "tournament";
    const SEASON = "tournament_season";
    const GROUP = "stage_group";
    const STAGE = "tournament_season_stage";
    const COUNTRY = "country";
    const PLAYER = "player";
    const COACH = "coach";
    const LINEUP_PLAYER_TYPE = "event_player_type";
    const MATCH_STATUS = "event_status";
    const VENUE = "venue";
    const REFEREE = "referee";
    const CITY = "city";
    const PRESIDENT = "president";
    const LINEUP = "lineup";
    const MATCH = "match";
    const ASSET = "asset";
    const STANDING_RULE = "standing_rule";
    const ROUND_TYPE = 'round_type';
}