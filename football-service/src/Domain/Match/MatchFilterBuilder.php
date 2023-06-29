<?php


namespace Sportal\FootballApi\Domain\Match;


use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;

class MatchFilterBuilder implements IMatchFilterBuilder
{

    private ?array $tournamentIds = null;

    private ?array $seasonIds = null;

    private ?array $stageIds = null;

    private ?array $groupIds = null;

    private ?array $roundIds = null;

    private ?\DateTimeImmutable $fromKickoffTime = null;

    private ?\DateTimeImmutable $toKickoffTime = null;

    private ?array $teamIds = null;

    private ?array $statusTypes = null;

    private ?array $statusCodes = null;

    private ?string $refereeId = null;

    private ?string $venueId = null;

    private ?string $sortDirection = null;

    private ?string $tournamentGroup = null;

    private ?array $matchIds = null;

    private ?array $roundFilter = null;

    /**
     * @param array|null $roundFilter
     * @return MatchFilterBuilder
     */
    public function setRoundFilter(?array $roundFilter): MatchFilterBuilder
    {
        $this->roundFilter = $roundFilter;
        return $this;
    }

    /**
     * @param array|null $tournamentIds
     * @return MatchFilterBuilder
     */
    public function setTournamentIds(?array $tournamentIds): MatchFilterBuilder
    {
        $this->tournamentIds = $tournamentIds;
        return $this;
    }

    /**
     * @param array|null $seasonIds
     * @return MatchFilterBuilder
     */
    public function setSeasonIds(?array $seasonIds): MatchFilterBuilder
    {
        $this->seasonIds = $seasonIds;
        return $this;
    }

    /**
     * @param array|null $stageIds
     * @return MatchFilterBuilder
     */
    public function setStageIds(?array $stageIds): MatchFilterBuilder
    {
        $this->stageIds = $stageIds;
        return $this;
    }

    /**
     * @param array|null $groupIds
     * @return MatchFilterBuilder
     */
    public function setGroupIds(?array $groupIds): MatchFilterBuilder
    {
        $this->groupIds = $groupIds;
        return $this;
    }

    /**
     * @param array|null $roundIds
     * @return MatchFilterBuilder
     */
    public function setRoundIds(?array $roundIds): MatchFilterBuilder
    {
        $this->roundIds = $roundIds;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $fromKickoffTime
     * @return MatchFilterBuilder
     */
    public function setFromKickoffTime(?\DateTimeImmutable $fromKickoffTime): MatchFilterBuilder
    {
        $this->fromKickoffTime = $fromKickoffTime;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $toKickoffTime
     * @return MatchFilterBuilder
     */
    public function setToKickoffTime(?\DateTimeImmutable $toKickoffTime): MatchFilterBuilder
    {
        $this->toKickoffTime = $toKickoffTime;
        return $this;
    }

    /**
     * @param array|null $teamIds
     * @return MatchFilterBuilder
     */
    public function setTeamIds(?array $teamIds): MatchFilterBuilder
    {
        $this->teamIds = $teamIds;
        return $this;
    }

    /**
     * @param MatchStatusType[]|null $statusTypes
     * @return MatchFilterBuilder
     */
    public function setStatusTypes(?array $statusTypes): MatchFilterBuilder
    {
        $this->statusTypes = $statusTypes;
        return $this;
    }

    /**
     * @param array|null $statusCodes
     * @return MatchFilterBuilder
     */
    public function setStatusCodes(?array $statusCodes): MatchFilterBuilder
    {
        $this->statusCodes = $statusCodes;
        return $this;
    }

    /**
     * @param string|null $refereeId
     * @return MatchFilterBuilder
     */
    public function setRefereeId(?string $refereeId): MatchFilterBuilder
    {
        $this->refereeId = $refereeId;
        return $this;
    }

    /**
     * @param string|null $venueId
     * @return MatchFilterBuilder
     */
    public function setVenueId(?string $venueId): MatchFilterBuilder
    {
        $this->venueId = $venueId;
        return $this;
    }

    /**
     * @param string|null $sortDirection
     * @return MatchFilterBuilder
     */
    public function setSortDirection(?string $sortDirection): MatchFilterBuilder
    {
        $this->sortDirection = $sortDirection;
        return $this;
    }

    public function setTournamentGroup(?string $tournamentGroup): MatchFilterBuilder
    {
        $this->tournamentGroup = $tournamentGroup;
        return $this;
    }

    public function setMatchIds(?array $matchIds): MatchFilterBuilder
    {
        $this->matchIds = $matchIds;
        return $this;
    }

    public function create(): MatchFilter
    {
        return new MatchFilter(
            $this->tournamentIds,
            $this->seasonIds,
            $this->stageIds,
            $this->groupIds,
            $this->roundIds,
            $this->fromKickoffTime,
            $this->toKickoffTime,
            $this->teamIds,
            $this->statusTypes,
            $this->statusCodes,
            $this->refereeId,
            $this->venueId,
            $this->sortDirection,
            $this->tournamentGroup,
            $this->matchIds,
            $this->roundFilter
        );
    }

}