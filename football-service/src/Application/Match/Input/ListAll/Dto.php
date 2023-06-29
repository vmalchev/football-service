<?php


namespace Sportal\FootballApi\Application\Match\Input\ListAll;


use DateTimeImmutable;
use Sportal\FootballApi\Application\IDto;

class Dto implements IDto
{

    private ?array $tournamentIds = null;

    private ?array $seasonIds = null;

    private ?array $stageIds = null;

    private ?array $groupIds = null;

    private ?array $roundIds = null;

    private ?DateTimeImmutable $fromKickoffTime = null;

    private ?DateTimeImmutable $toKickoffTime = null;

    private ?array $teamIds = null;

    private ?array $statusTypes = null;

    private ?array $statusCodes = null;

    private ?string $refereeId = null;

    private ?string $venueId = null;

    private ?string $sortDirection = null;

    /**
     * @var RoundFilterDto[]|null
     */
    private ?array $roundFilters = null;

    /**
     * @return RoundFilterDto[]|null
     */
    public function getRoundFilters(): ?array
    {
        return $this->roundFilters;
    }

    /**
     * @param RoundFilterDto[]|null $roundFilters
     */
    public function setRoundFilters(?array $roundFilters): void
    {
        $this->roundFilters = $roundFilters;
    }

    /**
     * @return array|null
     */
    public function getTournamentIds(): ?array
    {
        return $this->tournamentIds;
    }

    /**
     * @param array|null $tournamentIds
     */
    public function setTournamentIds(?array $tournamentIds): void
    {
        $this->tournamentIds = $tournamentIds;
    }

    /**
     * @return array|null
     */
    public function getSeasonIds(): ?array
    {
        return $this->seasonIds;
    }

    /**
     * @param array|null $seasonIds
     */
    public function setSeasonIds(?array $seasonIds): void
    {
        $this->seasonIds = $seasonIds;
    }

    /**
     * @return array|null
     */
    public function getStageIds(): ?array
    {
        return $this->stageIds;
    }

    /**
     * @param array|null $stageIds
     */
    public function setStageIds(?array $stageIds): void
    {
        $this->stageIds = $stageIds;
    }

    /**
     * @return array|null
     */
    public function getGroupIds(): ?array
    {
        return $this->groupIds;
    }

    /**
     * @param array|null $groupIds
     */
    public function setGroupIds(?array $groupIds): void
    {
        $this->groupIds = $groupIds;
    }

    /**
     * @return array|null
     */
    public function getRoundIds(): ?array
    {
        return $this->roundIds;
    }

    /**
     * @param array|null $roundIds
     */
    public function setRoundIds(?array $roundIds): void
    {
        $this->roundIds = $roundIds;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getFromKickoffTime(): ?DateTimeImmutable
    {
        return $this->fromKickoffTime;
    }

    /**
     * @param DateTimeImmutable|null $fromKickoffTime
     */
    public function setFromKickoffTime(?DateTimeImmutable $fromKickoffTime): void
    {
        $this->fromKickoffTime = $fromKickoffTime;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getToKickoffTime(): ?DateTimeImmutable
    {
        return $this->toKickoffTime;
    }

    /**
     * @param DateTimeImmutable|null $toKickoffTime
     */
    public function setToKickoffTime(?DateTimeImmutable $toKickoffTime): void
    {
        $this->toKickoffTime = $toKickoffTime;
    }

    /**
     * @return array|null
     */
    public function getTeamIds(): ?array
    {
        return $this->teamIds;
    }

    /**
     * @param array|null $teamIds
     */
    public function setTeamIds(?array $teamIds): void
    {
        $this->teamIds = $teamIds;
    }

    /**
     * @return array|null
     */
    public function getStatusTypes(): ?array
    {
        return $this->statusTypes;
    }

    /**
     * @param array|null $statusTypes
     */
    public function setStatusTypes(?array $statusTypes): void
    {
        $this->statusTypes = $statusTypes;
    }

    /**
     * @return array|null
     */
    public function getStatusCodes(): ?array
    {
        return $this->statusCodes;
    }

    /**
     * @param array|null $statusCodes
     */
    public function setStatusCodes(?array $statusCodes): void
    {
        $this->statusCodes = $statusCodes;
    }

    /**
     * @return string|null
     */
    public function getRefereeId(): ?string
    {
        return $this->refereeId;
    }

    /**
     * @param string|null $refereeId
     */
    public function setRefereeId(?string $refereeId): void
    {
        $this->refereeId = $refereeId;
    }

    /**
     * @return string|null
     */
    public function getVenueId(): ?string
    {
        return $this->venueId;
    }

    /**
     * @param string|null $venueId
     */
    public function setVenueId(?string $venueId): void
    {
        $this->venueId = $venueId;
    }

    /**
     * @return string|null
     */
    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    /**
     * @param string|null $sortDirection
     */
    public function setSortDirection(?string $sortDirection): void
    {
        $this->sortDirection = $sortDirection;
    }

}