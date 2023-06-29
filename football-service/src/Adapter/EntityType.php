<?php

namespace Sportal\FootballApi\Adapter;

class EntityType extends \MyCLabs\Enum\Enum
{
    private const PLAYER = 'player';
    private const TEAM = 'team';
    private const COACH = 'coach';
    private const MATCH = 'event';

    private const SEASON = 'tournament_season';

    private const VENUE = 'venue';
    private const REFEREE = 'referee';

    private const TOURNAMENT = 'tournament';

    private const CITY = 'city';

    private const PRESIDENT = 'president';

    private const STAGE = 'tournament_season_stage';
    private const GROUP = 'stage_group';

    private const COUNTRY = 'country';

    private const ODD_PROVIDER = 'odd_provider';
}