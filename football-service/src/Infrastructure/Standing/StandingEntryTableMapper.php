<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


class StandingEntryTableMapper
{
    const TABLE_NAME = 'standing_data';
    const FIELD_STANDING_ID = 'standing_id';
    const FIELD_TEAM_ID = 'team_id';
    const FIELD_PLAYER_ID = 'player_id';
    const FIELD_RANK = 'rank';
    const FIELD_DATA = 'data';
    const FIELD_WINS = 'wins';
    const FIELD_DRAWS = 'draws';
    const FIELD_LOSSES = 'defeits';
    const FIELD_PLAYED = 'played';
    const FIELD_POINTS = 'points';
    const FIELD_GOALS_FOR = 'goals_for';
    const FIELD_GOALS_AGAINST = 'goals_against';
    const FIELD_GOALS = 'goals';
    const FIELD_ASSISTS = 'assists';
    const FIELD_SCORED_FIRST = 'scored_first';
    const FIELD_PENALTIES = 'penalties';
    const FIELD_MINUTES = 'minutes';
    const FIELD_YELLOW_CARDS = 'yellow_cards';
    const FIELD_RED_CARDS = 'red_cards';


    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return [
            self::FIELD_STANDING_ID,
            self::FIELD_TEAM_ID,
            self::FIELD_PLAYER_ID,
            self::FIELD_RANK,
            self::FIELD_DATA
        ];
    }
}