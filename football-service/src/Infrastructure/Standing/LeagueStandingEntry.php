<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\ILeagueStandingEntry;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class LeagueStandingEntry implements ILeagueStandingEntry, IDatabaseEntity
{
    private ?string $standingId;

    private string $teamId;

    private int $rank;

    private int $played;

    private int $wins;

    private int $draws;

    private int $losses;

    private int $points;

    private int $goals_for;

    private int $goals_against;

    /**
     * LeagueStandingEntry constructor.
     * @param string|null $standingId
     * @param string $teamId
     * @param int $rank
     * @param int $played
     * @param int $wins
     * @param int $draws
     * @param int $losses
     * @param int $points
     * @param int $goals_for
     * @param int $goals_against
     */
    public function __construct(?string $standingId,
                                string $teamId,
                                int $rank,
                                int $played,
                                int $wins,
                                int $draws,
                                int $losses,
                                int $points,
                                int $goals_for,
                                int $goals_against)
    {
        $this->standingId = $standingId;
        $this->teamId = $teamId;
        $this->rank = $rank;
        $this->played = $played;
        $this->wins = $wins;
        $this->draws = $draws;
        $this->losses = $losses;
        $this->points = $points;
        $this->goals_for = $goals_for;
        $this->goals_against = $goals_against;
    }


    /**
     * @return string|null
     */
    public function getStandingId(): ?string
    {
        return $this->standingId;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->teamId;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @return int
     */
    public function getPlayed(): int
    {
        return $this->played;
    }

    /**
     * @return int
     */
    public function getWins(): int
    {
        return $this->wins;
    }

    /**
     * @return int
     */
    public function getDraws(): int
    {
        return $this->draws;
    }

    /**
     * @return int
     */
    public function getLosses(): int
    {
        return $this->losses;
    }

    /**
     * @return int
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getGoalsFor(): int
    {
        return $this->goals_for;
    }

    /**
     * @return int
     */
    public function getGoalsAgainst(): int
    {
        return $this->goals_against;
    }

    public function getPlayerId(): ?string
    {
        return null;
    }

    /**
     * @param string $standingId
     * @return LeagueStandingEntry
     */
    public function withStandingId(string $standingId): LeagueStandingEntry
    {
        $updated = clone $this;
        $updated->standingId = $standingId;
        return $updated;
    }

    /**
     * @inheritDoc
     */
    public function getDatabaseEntry(): array
    {
        $data = [
            StandingEntryTableMapper::FIELD_PLAYED => $this->played,
            StandingEntryTableMapper::FIELD_LOSSES => $this->losses,
            StandingEntryTableMapper::FIELD_WINS => $this->wins,
            StandingEntryTableMapper::FIELD_DRAWS => $this->draws,
            StandingEntryTableMapper::FIELD_POINTS => $this->points,
            StandingEntryTableMapper::FIELD_GOALS_FOR => $this->goals_for,
            StandingEntryTableMapper::FIELD_GOALS_AGAINST => $this->goals_against
        ];

        return [
            StandingEntryTableMapper::FIELD_TEAM_ID => $this->teamId,
            StandingEntryTableMapper::FIELD_STANDING_ID => $this->standingId,
            StandingEntryTableMapper::FIELD_PLAYER_ID => null,
            StandingEntryTableMapper::FIELD_RANK => $this->rank,
            StandingEntryTableMapper::FIELD_DATA => json_encode($data)
        ];
    }

    /**
     * @inheritDoc
     */
    public function getPrimaryKey(): array
    {
        return [
            StandingEntryTableMapper::FIELD_TEAM_ID => $this->teamId,
            StandingEntryTableMapper::FIELD_STANDING_ID => $this->standingId,
            StandingEntryTableMapper::FIELD_PLAYER_ID => null,
        ];
    }
}