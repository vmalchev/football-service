<?php

namespace Sportal\FootballApi\Domain\Standing;

interface ILeagueStandingEntryFactory
{
    public function setEmpty(): ILeagueStandingEntryFactory;

    /**
     * @param string $teamId
     * @return ILeagueStandingEntryFactory
     */
    public function setTeamId(string $teamId): ILeagueStandingEntryFactory;

    /**
     * @param int $rank
     * @return ILeagueStandingEntryFactory
     */
    public function setRank(int $rank): ILeagueStandingEntryFactory;

    /**
     * @param int $played
     * @return ILeagueStandingEntryFactory
     */
    public function setPlayed(int $played): ILeagueStandingEntryFactory;

    /**
     * @param int $wins
     * @return ILeagueStandingEntryFactory
     */
    public function setWins(int $wins): ILeagueStandingEntryFactory;

    /**
     * @param int $draws
     * @return ILeagueStandingEntryFactory
     */
    public function setDraws(int $draws): ILeagueStandingEntryFactory;

    /**
     * @param int $losses
     * @return ILeagueStandingEntryFactory
     */
    public function setLosses(int $losses): ILeagueStandingEntryFactory;

    /**
     * @param int $points
     * @return ILeagueStandingEntryFactory
     */
    public function setPoints(int $points): ILeagueStandingEntryFactory;

    /**
     * @param int $goals_for
     * @return ILeagueStandingEntryFactory
     */
    public function setGoalsFor(int $goals_for): ILeagueStandingEntryFactory;

    /**
     * @param int $goals_against
     * @return ILeagueStandingEntryFactory
     */
    public function setGoalsAgainst(int $goals_against): ILeagueStandingEntryFactory;

    public function create(): ILeagueStandingEntry;
}