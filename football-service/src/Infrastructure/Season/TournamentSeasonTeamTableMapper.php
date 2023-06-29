<?php


namespace Sportal\FootballApi\Infrastructure\Season;

use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;


class TournamentSeasonTeamTableMapper
{
    const TABLE_NAME = "tournament_season_team";

    const FIELD_ID = "id";
    const FIELD_TOURNAMENT_SEASON_ID = "tournament_season_id";
    const FIELD_TEAM_ID = "team_id";

    private JoinFactory $joinFactory;
    /**
     * @param JoinFactory $joinFactory
     */
    public function __construct(JoinFactory $joinFactory)
    {
        $this->joinFactory = $joinFactory;
    }

    public static function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_TOURNAMENT_SEASON_ID,
            self::FIELD_TEAM_ID
        ];
    }

    public function getInnerJoin()
    {
        return $this->joinFactory
        ->createLeft(self::TABLE_NAME, [])
        ->setForeignKey(self::FIELD_ID)
        ->setReference(self::FIELD_TOURNAMENT_SEASON_ID);
    }
}