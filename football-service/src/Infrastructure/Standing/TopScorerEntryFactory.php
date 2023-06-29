<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\ITopScorerEntry;
use Sportal\FootballApi\Domain\Standing\ITopScorerEntryFactory;

class TopScorerEntryFactory implements ITopScorerEntryFactory
{
    private ?string $standingId = null;

    private string $teamId;

    private string $playerId;

    private int $rank;

    private int $goals;

    private ?int $played = null;

    private ?int $assists = null;

    private ?int $scored_first = null;

    private ?int $minutes = null;

    private ?int $penalties = null;

    private ?int $yellow_cards = null;

    private ?int $red_cards = null;

    public function setEmpty(): ITopScorerEntryFactory
    {
        return new TopScorerEntryFactory();
    }

    /**
     * @param string|null $standingId
     * @return ITopScorerEntryFactory
     */
    public function setStandingId(?string $standingId): ITopScorerEntryFactory
    {
        $this->standingId = $standingId;
        return $this;
    }

    /**
     * @param string $teamId
     * @return ITopScorerEntryFactory
     */
    public function setTeamId(string $teamId): ITopScorerEntryFactory
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param string $playerId
     * @return ITopScorerEntryFactory
     */
    public function setPlayerId(string $playerId): ITopScorerEntryFactory
    {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * @param int $rank
     * @return ITopScorerEntryFactory
     */
    public function setRank(int $rank): ITopScorerEntryFactory
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @param int $goals
     * @return ITopScorerEntryFactory
     */
    public function setGoals(int $goals): ITopScorerEntryFactory
    {
        $this->goals = $goals;
        return $this;
    }

    /**
     * @param int|null $played
     * @return ITopScorerEntryFactory
     */
    public function setPlayed(?int $played): ITopScorerEntryFactory
    {
        $this->played = $played;
        return $this;
    }

    /**
     * @param int|null $assists
     * @return ITopScorerEntryFactory
     */
    public function setAssists(?int $assists): ITopScorerEntryFactory
    {
        $this->assists = $assists;
        return $this;
    }

    /**
     * @param int|null $scored_first
     * @return ITopScorerEntryFactory
     */
    public function setScoredFirst(?int $scored_first): ITopScorerEntryFactory
    {
        $this->scored_first = $scored_first;
        return $this;
    }

    /**
     * @param int|null $minutes
     * @return ITopScorerEntryFactory
     */
    public function setMinutes(?int $minutes): ITopScorerEntryFactory
    {
        $this->minutes = $minutes;
        return $this;
    }

    /**
     * @param int|null $penalties
     * @return ITopScorerEntryFactory
     */
    public function setPenalties(?int $penalties): ITopScorerEntryFactory
    {
        $this->penalties = $penalties;
        return $this;
    }

    /**
     * @param int|null $yellow_cards
     * @return ITopScorerEntryFactory
     */
    public function setYellowCards(?int $yellow_cards): ITopScorerEntryFactory
    {
        $this->yellow_cards = $yellow_cards;
        return $this;
    }

    public function setRedCards(?int $red_cards): ITopScorerEntryFactory
    {
        $this->red_cards = $red_cards;
        return $this;
    }

    public function create(): ITopScorerEntry
    {
        return new TopScorerEntry(
            $this->standingId,
            $this->teamId,
            $this->playerId,
            $this->rank,
            $this->goals,
            $this->played,
            $this->assists,
            $this->scored_first,
            $this->minutes,
            $this->penalties,
            $this->yellow_cards,
            $this->red_cards
        );
    }
}