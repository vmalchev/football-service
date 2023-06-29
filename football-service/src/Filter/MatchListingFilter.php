<?php

namespace Sportal\FootballApi\Filter;

class MatchListingFilter
{
    private $matchIds;
    private $teamIds;
    private $tournamentIds;
    private $seasonIds;
    private $rounds;
    private $stageIds;
    private $teamNames;

    /**
     * @var string|null
     */
    private $tournamentOrder;

    private $statusTypes;
    private $fromStartTime;
    private $toStartTime;
    private $updatedTime;

    private ?string $refereeId;
    private ?string $venueId;
    private ?string $statusCode;


    /**
     * @param array $matchIds
     * @param \DateTimeImmutable|null $fromStartTime
     * @param \DateTimeImmutable|null $toStartTime
     * @param \DateTimeImmutable|null $updatedTime
     * @param array $teamIds
     * @param array $tournamentIds
     * @param array $seasonIds
     * @param array $statusTypes
     * @param array $rounds
     * @param array $stageIds
     * @param array $teamNames
     * @param string|null $tournamentOrder
     * @param string|null $refereeId
     * @param string|null $venueId
     * @param string|null $statusCode
     */
    public function __construct(
        array $matchIds = [],
        \DateTimeImmutable $fromStartTime = null,
        \DateTimeImmutable $toStartTime = null,
        \DateTimeImmutable $updatedTime = null,
        array $teamIds = [],
        array $tournamentIds = [],
        array $seasonIds = [],
        array $statusTypes = [],
        array $rounds = [],
        array $stageIds = [],
        array $teamNames = [],
        ?string $tournamentOrder = null,
        ?string $refereeId = null,
        ?string $venueId = null,
        ?string $statusCode = null
    )
    {
        $this->matchIds = $matchIds;
        $this->teamIds = $teamIds;
        $this->seasonIds = $seasonIds;
        $this->tournamentIds = $tournamentIds;
        $this->statusTypes = $statusTypes;
        $this->fromStartTime = $fromStartTime;
        $this->toStartTime = $toStartTime;
        $this->updatedTime = $updatedTime;
        $this->rounds = $rounds;
        $this->stageIds = $stageIds;
        $this->teamNames = $teamNames;
        $this->tournamentOrder = $tournamentOrder;
        $this->refereeId = $refereeId;
        $this->venueId = $venueId;
        $this->statusCode = $statusCode;
    }

    /**
     * @return array
     */
    public function getMatchIds(): array
    {
        return $this->matchIds;
    }

    /**
     * @return array
     */
    public function getTeamIds(): array
    {
        return $this->teamIds;
    }

    /**
     * @return array
     */
    public function getTournamentIds(): array
    {
        return $this->tournamentIds;
    }

    /**
     * @return null
     */
    public function getFromStartTime()
    {
        return $this->fromStartTime;
    }

    /**
     * @return null
     */
    public function getToStartTime()
    {
        return $this->toStartTime;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * Checks whether all properties in the filter are empty
     * @return bool
     */
    public function isEmpty()
    {
        foreach (get_object_vars($this) as $property) {
            if (!empty($property)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function getSeasonIds(): array
    {
        return $this->seasonIds;
    }

    /**
     * @return array
     */
    public function getStatusTypes(): array
    {
        return $this->statusTypes;
    }

    /**
     * @return array
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * @return array
     */
    public function getStageIds()
    {
        return $this->stageIds;
    }

    /**
     * @return array
     */
    public function getTeamNames()
    {
        return $this->teamNames;
    }

    /**
     * @return string|null
     */
    public function getTournamentOrder(): ?string
    {
        return $this->tournamentOrder;
    }

    /**
     * @param array $matchIds
     * @return MatchListingFilter
     */
    public function setMatchIds(array $matchIds): MatchListingFilter
    {
        $this->matchIds = $matchIds;
        return $this;
    }

    /**
     * @param array $teamIds
     * @return MatchListingFilter
     */
    public function setTeamIds(array $teamIds): MatchListingFilter
    {
        $this->teamIds = $teamIds;
        return $this;
    }

    /**
     * @param array $tournamentIds
     * @return MatchListingFilter
     */
    public function setTournamentIds(array $tournamentIds): MatchListingFilter
    {
        $this->tournamentIds = $tournamentIds;
        return $this;
    }

    /**
     * @param array $seasonIds
     * @return MatchListingFilter
     */
    public function setSeasonIds(array $seasonIds): MatchListingFilter
    {
        $this->seasonIds = $seasonIds;
        return $this;
    }

    /**
     * @param array $rounds
     * @return MatchListingFilter
     */
    public function setRounds(array $rounds): MatchListingFilter
    {
        $this->rounds = $rounds;
        return $this;
    }

    /**
     * @param array $stageIds
     * @return MatchListingFilter
     */
    public function setStageIds(array $stageIds): MatchListingFilter
    {
        $this->stageIds = $stageIds;
        return $this;
    }

    /**
     * @param array $teamNames
     * @return MatchListingFilter
     */
    public function setTeamNames(array $teamNames): MatchListingFilter
    {
        $this->teamNames = $teamNames;
        return $this;
    }

    /**
     * @param string|null $tournamentOrder
     * @return MatchListingFilter
     */
    public function setTournamentOrder(?string $tournamentOrder): MatchListingFilter
    {
        $this->tournamentOrder = $tournamentOrder;
        return $this;
    }

    /**
     * @param array $statusTypes
     * @return MatchListingFilter
     */
    public function setStatusTypes(array $statusTypes): MatchListingFilter
    {
        $this->statusTypes = $statusTypes;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $fromStartTime
     * @return MatchListingFilter
     */
    public function setFromStartTime(?\DateTimeImmutable $fromStartTime): MatchListingFilter
    {
        $this->fromStartTime = $fromStartTime;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $toStartTime
     * @return MatchListingFilter
     */
    public function setToStartTime(?\DateTimeImmutable $toStartTime): MatchListingFilter
    {
        $this->toStartTime = $toStartTime;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $updatedTime
     * @return MatchListingFilter
     */
    public function setUpdatedTime(?\DateTimeImmutable $updatedTime): MatchListingFilter
    {
        $this->updatedTime = $updatedTime;
        return $this;
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
     * @return MatchListingFilter
     */
    public function setRefereeId(?string $refereeId): MatchListingFilter
    {
        $this->refereeId = $refereeId;
        return $this;
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
     * @return MatchListingFilter
     */
    public function setVenueId(?string $venueId): MatchListingFilter
    {
        $this->venueId = $venueId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }

    /**
     * @param string|null $statusCode
     * @return MatchListingFilter
     */
    public function setStatusCode(?string $statusCode): MatchListingFilter
    {
        $this->statusCode = $statusCode;
        return $this;
    }


}
