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

interface IMatchEntityFactory
{
    /**
     * @param string|null $id
     * @return IMatchEntityFactory
     */
    public function setId(?string $id): IMatchEntityFactory;

    /**
     * @param string $statusId
     * @return IMatchEntityFactory
     */
    public function setStatusId(string $statusId): IMatchEntityFactory;

    /**
     * @param IMatchStatusEntity|null $status
     * @return IMatchEntityFactory
     */
    public function setStatus(?IMatchStatusEntity $status): IMatchEntityFactory;

    /**
     * @param DateTimeInterface $kickoffTime
     * @return IMatchEntityFactory
     */
    public function setKickoffTime(DateTimeInterface $kickoffTime): IMatchEntityFactory;

    /**
     * @param string $stageId
     * @return IMatchEntityFactory
     */
    public function setStageId(string $stageId): IMatchEntityFactory;

    /**
     * @param IStageEntity|null $stage
     * @return IMatchEntityFactory
     */
    public function setStage(?IStageEntity $stage): IMatchEntityFactory;

    /**
     * @param string $groupId
     * @return IMatchEntityFactory
     */
    public function setGroupId(string $groupId): IMatchEntityFactory;

    /**
     * @param IGroupEntity|null $group
     * @return IMatchEntityFactory
     */
    public function setGroup(?IGroupEntity $group): IMatchEntityFactory;

    /**
     * @param string|null $roundKey
     * @return IMatchEntityFactory
     */
    public function setRoundKey(?string $roundKey): IMatchEntityFactory;

    /**
     * @param string|null $homeTeamId
     * @return IMatchEntityFactory
     */
    public function setHomeTeamId(?string $homeTeamId): IMatchEntityFactory;

    /**
     * @param string|null $awayTeamId
     * @return IMatchEntityFactory
     */
    public function setAwayTeamId(?string $awayTeamId): IMatchEntityFactory;

    /**
     * @param ITeamEntity|null $homeTeam
     * @return IMatchEntityFactory
     */
    public function setHomeTeam(?ITeamEntity $homeTeam): IMatchEntityFactory;

    /**
     * @param ITeamEntity|null $awayTeam
     * @return IMatchEntityFactory
     */
    public function setAwayTeam(?ITeamEntity $awayTeam): IMatchEntityFactory;

    /**
     * @param string|null $venueId
     * @return IMatchEntityFactory
     */
    public function setVenueId(?string $venueId): IMatchEntityFactory;

    /**
     * @param IVenueEntity|null $venue
     * @return IMatchEntityFactory
     */
    public function setVenue(?IVenueEntity $venue): IMatchEntityFactory;

    /**
     * @param MatchCoverage|null $coverage
     * @return IMatchEntityFactory
     */
    public function setCoverage(?MatchCoverage $coverage): IMatchEntityFactory;

    /**
     * @param int|null $spectators
     * @return IMatchEntityFactory
     */
    public function setSpectators(?int $spectators): IMatchEntityFactory;

    /**
     * @param IMatchRefereeEntity[]|null $referees
     * @return IMatchEntityFactory
     */
    public function setReferees(?array $referees): IMatchEntityFactory;

    /**
     * @param IMatchScore|null $score
     * @return IMatchEntityFactory
     */
    public function setScore(?IMatchScore $score): IMatchEntityFactory;

    /**
     * @param string|null $roundTypeId
     * @return IMatchEntityFactory
     */
    public function setRoundTypeId(?string $roundTypeId): IMatchEntityFactory;

    /**
     * @param IRoundEntity|null $round
     * @return IMatchEntityFactory|null
     */
    public function setRound(?IRoundEntity $round): ?IMatchEntityFactory;
    
    public function setPhaseStartedAt(DateTimeInterface $phaseStartedAt): IMatchEntityFactory;

    public function setFinishedAt(DateTimeInterface $finishedAt): IMatchEntityFactory;

    public function setColors(?ITeamColorsEntity $colors): IMatchEntityFactory;

    public function setEmpty(): IMatchEntityFactory;

    public function setFrom(IMatchEntity $matchEntity): IMatchEntityFactory;

    public function create(): IMatchEntity;
}