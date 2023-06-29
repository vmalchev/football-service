<?php

namespace Sportal\FootballApi\Domain\Match;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;

interface IMatchEntity
{
    public function getId(): ?string;

    public function getStatusId(): string;

    public function getStatus(): ?IMatchStatusEntity;

    public function getKickoffTime(): DateTimeInterface;

    public function getStageId(): string;

    public function getStage(): ?IStageEntity;

    public function getGroupId(): ?string;

    public function getGroup(): ?IGroupEntity;

    public function getRoundKey(): ?string;

    public function getHomeTeamId(): ?string;

    public function getHomeTeam(): ?ITeamEntity;

    public function getAwayTeamId(): ?string;

    public function getAwayTeam(): ?ITeamEntity;

    public function getVenueId(): ?string;

    public function getVenue(): ?IVenueEntity;

    public function getCoverage(): ?MatchCoverage;

    public function getSpectators(): ?int;

    public function getScore(): ?IMatchScore;
    
    /**
     * @return IMatchRefereeEntity[]
     */
    public function getReferees(): ?array;

    public function getPhaseStartedAt(): ?DateTimeInterface;

    public function getFinishedAt(): ?DateTimeInterface;

    public function getColors(): ?ITeamColorsEntity;

    /**
     * @return string|null
     */
    public function getRoundTypeId(): ?string;

    /**
     * @return IRoundEntity|null
     */
    public function getRound(): ?IRoundEntity;
}
