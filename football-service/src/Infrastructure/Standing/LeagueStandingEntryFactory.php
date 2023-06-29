<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\ILeagueStandingEntry;
use Sportal\FootballApi\Domain\Standing\ILeagueStandingEntryFactory;

class LeagueStandingEntryFactory implements ILeagueStandingEntryFactory
{
    private ?string $standingId = null;

    private string $teamId;

    private int $rank;

    private int $played;

    private int $wins;

    private int $draws;

    private int $losses;

    private int $points;

    private int $goals_for;

    private int $goals_against;

    public function setEmpty(): ILeagueStandingEntryFactory
    {
        return new LeagueStandingEntryFactory();
    }

    /**
     * @param string|null $standingId
     * @return ILeagueStandingEntryFactory
     */
    public function setStandingId(?string $standingId): ILeagueStandingEntryFactory
    {
        $this->standingId = $standingId;
        return $this;
    }

    /**
     * @param string $teamId
     * @return ILeagueStandingEntryFactory
     */
    public function setTeamId(string $teamId): ILeagueStandingEntryFactory
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param int $rank
     * @return ILeagueStandingEntryFactory
     */
    public function setRank(int $rank): ILeagueStandingEntryFactory
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @param int $played
     * @return ILeagueStandingEntryFactory
     */
    public function setPlayed(int $played): ILeagueStandingEntryFactory
    {
        $this->played = $played;
        return $this;
    }

    /**
     * @param int $wins
     * @return ILeagueStandingEntryFactory
     */
    public function setWins(int $wins): ILeagueStandingEntryFactory
    {
        $this->wins = $wins;
        return $this;
    }

    /**
     * @param int $draws
     * @return ILeagueStandingEntryFactory
     */
    public function setDraws(int $draws): ILeagueStandingEntryFactory
    {
        $this->draws = $draws;
        return $this;
    }

    /**
     * @param int $losses
     * @return ILeagueStandingEntryFactory
     */
    public function setLosses(int $losses): ILeagueStandingEntryFactory
    {
        $this->losses = $losses;
        return $this;
    }

    /**
     * @param int $points
     * @return ILeagueStandingEntryFactory
     */
    public function setPoints(int $points): ILeagueStandingEntryFactory
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @param int $goals_for
     * @return ILeagueStandingEntryFactory
     */
    public function setGoalsFor(int $goals_for): ILeagueStandingEntryFactory
    {
        $this->goals_for = $goals_for;
        return $this;
    }

    /**
     * @param int $goals_against
     * @return ILeagueStandingEntryFactory
     */
    public function setGoalsAgainst(int $goals_against): ILeagueStandingEntryFactory
    {
        $this->goals_against = $goals_against;
        return $this;
    }

    public function create(): ILeagueStandingEntry
    {
        return new LeagueStandingEntry($this->standingId,
            $this->teamId,
            $this->rank,
            $this->played,
            $this->wins,
            $this->draws,
            $this->losses,
            $this->points,
            $this->goals_for,
            $this->goals_against);
    }

}