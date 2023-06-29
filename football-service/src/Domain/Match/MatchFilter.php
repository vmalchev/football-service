<?php


namespace Sportal\FootballApi\Domain\Match;


use DateTimeImmutable;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;

class MatchFilter
{

    private ?array $tournamentIds;

    private ?array $seasonIds;

    private ?array $stageIds;

    private ?array $groupIds;

    private ?array $roundIds;

    private ?DateTimeImmutable $fromKickoffTime;

    private ?DateTimeImmutable $toKickoffTime;

    private ?array $teamIds;

    private ?array $statusTypes;

    private ?array $statusCodes;

    private ?string $refereeId;

    private ?string $venueId;

    private ?string $sortDirection;

    private ?string $tournamentGroup;

    private ?array $matchIds;

    private ?array $roundFilter;

    /**
     * MatchFilter constructor.
     * @param array|null $tournamentIds
     * @param array|null $seasonIds
     * @param array|null $stageIds
     * @param array|null $groupIds
     * @param array|null $roundIds
     * @param DateTimeImmutable|null $fromKickoffTime
     * @param DateTimeImmutable|null $toKickoffTime
     * @param array|null $teamIds
     * @param MatchStatusType[]|null $statusTypes
     * @param array|null $statusCodes
     * @param string|null $refereeId
     * @param string|null $venueId
     * @param string|null $sortDirection
     * @param string|null $tournamentGroup
     * @param array|null $matchIds
     * @param array|null $roundFilter
     */
    public function __construct(?array             $tournamentIds,
                                ?array             $seasonIds,
                                ?array             $stageIds,
                                ?array             $groupIds,
                                ?array             $roundIds,
                                ?DateTimeImmutable $fromKickoffTime,
                                ?DateTimeImmutable $toKickoffTime,
                                ?array             $teamIds,
                                ?array             $statusTypes,
                                ?array             $statusCodes,
                                ?string            $refereeId,
                                ?string            $venueId,
                                ?string            $sortDirection,
                                ?string            $tournamentGroup,
                                ?array             $matchIds,
                                ?array             $roundFilter)
    {
        $this->tournamentIds = $tournamentIds;
        $this->seasonIds = $seasonIds;
        $this->stageIds = $stageIds;
        $this->groupIds = $groupIds;
        $this->roundIds = $roundIds;
        $this->fromKickoffTime = $fromKickoffTime;
        $this->toKickoffTime = $toKickoffTime;
        $this->teamIds = $teamIds;
        $this->statusTypes = $statusTypes;
        $this->statusCodes = $statusCodes;
        $this->refereeId = $refereeId;
        $this->venueId = $venueId;
        $this->sortDirection = $sortDirection;
        $this->tournamentGroup = $tournamentGroup;
        $this->matchIds = $matchIds;
        $this->roundFilter = $roundFilter;
    }

    /**
     * @return array|null
     */
    public function getTournamentIds(): ?array
    {
        return $this->tournamentIds;
    }

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }

    /**
     * @return array|null
     */
    public function getStageIds(): ?array
    {
        return $this->stageIds;
    }

    /**
     * @return array|null
     */
    public function getGroupIds(): ?array
    {
        return $this->groupIds;
    }

    /**
     * @return array|null
     */
    public function getRoundIds(): ?array
    {
        return $this->roundIds;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFromKickoffTime(): ?DateTimeImmutable
    {
        return $this->fromKickoffTime;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getToKickoffTime(): ?DateTimeImmutable
    {
        return $this->toKickoffTime;
    }

    /**
     * @return array|null
     */
    public function getTeamIds(): ?array
    {
        return $this->teamIds;
    }

    /**
     * @return MatchStatusType[]|null
     */
    public function getStatusTypes(): ?array
    {
        return $this->statusTypes;
    }

    /**
     * @return array|null
     */
    public function getStatusCodes(): ?array
    {
        return $this->statusCodes;
    }

    /**
     * @return string|null
     */
    public function getRefereeId(): ?string
    {
        return $this->refereeId;
    }

    /**
     * @return string|null
     */
    public function getVenueId(): ?string
    {
        return $this->venueId;
    }

    /**
     * @return string|null
     */
    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    /**
     * @return string|null
     */
    public function getTournamentGroup(): ?string
    {
        return $this->tournamentGroup;
    }

    /**
     * @return array|null
     */
    public function getMatchIds(): ?array
    {
        return $this->matchIds;
    }

    /**
     * @return array|null
     */
    public function getRoundFilter(): ?array
    {
        return $this->roundFilter;
    }

}