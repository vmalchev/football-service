<?php

namespace Sportal\FootballApi\Domain\Standing;

interface ITopScorerEntryFactory
{
    public function setEmpty(): ITopScorerEntryFactory;

    /**
     * @param string $teamId
     * @return ITopScorerEntryFactory
     */
    public function setTeamId(string $teamId): ITopScorerEntryFactory;

    /**
     * @param string $playerId
     * @return ITopScorerEntryFactory
     */
    public function setPlayerId(string $playerId): ITopScorerEntryFactory;

    /**
     * @param int $rank
     * @return ITopScorerEntryFactory
     */
    public function setRank(int $rank): ITopScorerEntryFactory;

    /**
     * @param int $goals
     * @return ITopScorerEntryFactory
     */
    public function setGoals(int $goals): ITopScorerEntryFactory;

    /**
     * @param int|null $played
     * @return ITopScorerEntryFactory
     */
    public function setPlayed(?int $played): ITopScorerEntryFactory;

    /**
     * @param int|null $assists
     * @return ITopScorerEntryFactory
     */
    public function setAssists(?int $assists): ITopScorerEntryFactory;

    /**
     * @param int|null $scored_first
     * @return ITopScorerEntryFactory
     */
    public function setScoredFirst(?int $scored_first): ITopScorerEntryFactory;

    /**
     * @param int|null $minutes
     * @return ITopScorerEntryFactory
     */
    public function setMinutes(?int $minutes): ITopScorerEntryFactory;

    /**
     * @param int|null $penalties
     * @return ITopScorerEntryFactory
     */
    public function setPenalties(?int $penalties): ITopScorerEntryFactory;

    /**
     * @param int|null $yellow_cards
     * @return ITopScorerEntryFactory
     */
    public function setYellowCards(?int $yellow_cards): ITopScorerEntryFactory;

    /**
     * @param int|null $red_cards
     * @return ITopScorerEntryFactory
     */
    public function setRedCards(?int $red_cards): ITopScorerEntryFactory;

    public function create(): ITopScorerEntry;
}