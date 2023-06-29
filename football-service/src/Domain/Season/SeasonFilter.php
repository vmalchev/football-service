<?php


namespace Sportal\FootballApi\Domain\Season;

class SeasonFilter
{
    private ?string $seasonId = null;

    private ?string $tournamentId = null;

    private ?string $teamId = null;

    private ?SeasonStatus $status = null;

    private function __construct()
    {
        // avoid instances
    }

    /**
     * @param string|null $seasonId
     * @return SeasonFilter
     */
    public function setSeasonId(?string $seasonId): SeasonFilter
    {
        $this->seasonId = $seasonId;
        return $this;
    }

    /**
     * @param string|null $tournamentId
     * @return SeasonFilter
     */
    public function setTournamentId(?string $tournamentId): SeasonFilter
    {
        $this->tournamentId = $tournamentId;
        return $this;
    }

    /**
     * @param string|null $teamId
     * @return SeasonFilter
     */
    public function setTeamId(?string $teamId): SeasonFilter
    {
        $this->teamId = $teamId;
        return $this;
    }

    /**
     * @param SeasonStatus|null $status
     * @return SeasonFilter
     */
    public function setStatus(?SeasonStatus $status): SeasonFilter
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSeasonId(): ?string
    {
        return $this->seasonId;
    }

    /**
     * @return string|null
     */
    public function getTournamentId(): ?string
    {
        return $this->tournamentId;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->teamId;
    }

    /**
     * @return SeasonStatus|null
     */
    public function getStatus(): ?SeasonStatus
    {
        return $this->status;
    }

    public static function create(): SeasonFilter
    {
        return new SeasonFilter();
    }
}