<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\ITopScorerEntry;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class TopScorerEntry implements ITopScorerEntry, IDatabaseEntity
{
    private ?string $standingId;

    private string $teamId;

    private string $playerId;

    private int $rank;

    private int $goals;

    private ?int $played;

    private ?int $assists;

    private ?int $scored_first;

    private ?int $minutes;

    private ?int $penalties;

    private ?int $yellow_cards;

    private ?int $red_cards;

    /**
     * TopScorerEntry constructor.
     * @param string|null $standingId
     * @param string $teamId
     * @param string $playerId
     * @param int $rank
     * @param int $goals
     * @param int|null $played
     * @param int|null $assists
     * @param int|null $scored_first
     * @param int|null $minutes
     * @param int|null $penalties
     * @param int|null $yellow_cards
     * @param int|null $red_cards
     */
    public function __construct(
        ?string $standingId,
        string $teamId,
        string $playerId,
        int $rank,
        int $goals,
        ?int $played,
        ?int $assists,
        ?int $scored_first,
        ?int $minutes,
        ?int $penalties,
        ?int $yellow_cards,
        ?int $red_cards
    ) {
        $this->standingId = $standingId;
        $this->teamId = $teamId;
        $this->playerId = $playerId;
        $this->rank = $rank;
        $this->goals = $goals;
        $this->played = $played;
        $this->assists = $assists;
        $this->scored_first = $scored_first;
        $this->minutes = $minutes;
        $this->penalties = $penalties;
        $this->yellow_cards = $yellow_cards;
        $this->red_cards = $red_cards;
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
     * @return string
     */
    public function getPlayerId(): string
    {
        return $this->playerId;
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
    public function getGoals(): int
    {
        return $this->goals;
    }

    /**
     * @return int|null
     */
    public function getPlayed(): ?int
    {
        return $this->played;
    }

    /**
     * @return int|null
     */
    public function getAssists(): ?int
    {
        return $this->assists;
    }

    /**
     * @return int|null
     */
    public function getScoredFirst(): ?int
    {
        return $this->scored_first;
    }

    /**
     * @return int|null
     */
    public function getMinutes(): ?int
    {
        return $this->minutes;
    }

    /**
     * @return int|null
     */
    public function getPenalties(): ?int
    {
        return $this->penalties;
    }

    /**
     * @return int|null
     */
    public function getYellowCards(): ?int
    {
        return $this->yellow_cards;
    }

    /**
     * @return int|null
     */
    public function getRedCards(): ?int
    {
        return $this->red_cards;
    }

    /**
     * @param string $standingId
     * @return TopScorerEntry
     */
    public function withStandingId(string $standingId): TopScorerEntry
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
        $data = array_filter(
            [
                StandingEntryTableMapper::FIELD_PLAYED => $this->played,
                StandingEntryTableMapper::FIELD_MINUTES => $this->minutes,
                StandingEntryTableMapper::FIELD_GOALS => $this->goals,
                StandingEntryTableMapper::FIELD_PENALTIES => $this->penalties,
                StandingEntryTableMapper::FIELD_SCORED_FIRST => $this->scored_first,
                StandingEntryTableMapper::FIELD_ASSISTS => $this->assists,
                StandingEntryTableMapper::FIELD_YELLOW_CARDS => $this->yellow_cards,
                StandingEntryTableMapper::FIELD_RED_CARDS => $this->red_cards
            ], fn($value) => $value !== null
        );

        return [
            StandingEntryTableMapper::FIELD_TEAM_ID => $this->teamId,
            StandingEntryTableMapper::FIELD_STANDING_ID => $this->standingId,
            StandingEntryTableMapper::FIELD_PLAYER_ID => $this->playerId,
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
            StandingEntryTableMapper::FIELD_PLAYER_ID => $this->playerId,
        ];
    }
}