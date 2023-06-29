<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Match\IMatchEntityFactory;
use Sportal\FootballApi\Domain\Match\IMatchRefereeEntity;
use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;

class MatchEntityFactory implements IMatchEntityFactory
{
    private ?string $id = null;
    private string $statusId;
    private ?IMatchStatusEntity $status = null;
    private DateTimeInterface $kickoffTime;
    private string $stageId;
    private ?IStageEntity $stage = null;
    private ?string $groupId = null;
    private ?IGroupEntity $group = null;
    private ?string $roundKey = null;
    private ?string $homeTeamId = null;
    private ?string $awayTeamId = null;
    private ?ITeamEntity $homeTeam = null;
    private ?ITeamEntity $awayTeam = null;
    private ?string $venueId = null;
    private ?IVenueEntity $venue = null;
    private ?MatchCoverage $coverage = null;
    private ?int $spectators = null;
    /**
     * @var IMatchRefereeEntity[]|null
     */
    private ?array $referees = null;
    private ?IMatchScore $score = null;
    private ?DateTimeInterface $phaseStartedAt = null;
    private ?DateTimeInterface $finishedAt = null;
    private ?ITeamColorsEntity $colors = null;
    private ?string $roundTypeId = null;
    private ?IRoundEntity $round = null;

    /**
     * @param string|null $id
     * @return IMatchEntityFactory
     */
    public function setId(?string $id): IMatchEntityFactory
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $statusId
     * @return IMatchEntityFactory
     */
    public function setStatusId(string $statusId): IMatchEntityFactory
    {
        $this->statusId = $statusId;
        return $this;
    }

    /**
     * @param IMatchStatusEntity|null $status
     * @return IMatchEntityFactory
     */
    public function setStatus(?IMatchStatusEntity $status): IMatchEntityFactory
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @param DateTimeInterface $kickoffTime
     * @return IMatchEntityFactory
     */
    public function setKickoffTime(DateTimeInterface $kickoffTime): IMatchEntityFactory
    {
        $this->kickoffTime = $kickoffTime;
        return $this;
    }

    /**
     * @param string $stageId
     * @return IMatchEntityFactory
     */
    public function setStageId(string $stageId): IMatchEntityFactory
    {
        $this->stageId = $stageId;
        return $this;
    }

    /**
     * @param IStageEntity|null $stage
     * @return IMatchEntityFactory
     */
    public function setStage(?IStageEntity $stage): IMatchEntityFactory
    {
        $this->stage = $stage;
        return $this;
    }

    /**
     * @param string|null $groupId
     * @return IMatchEntityFactory
     */
    public function setGroupId(?string $groupId): IMatchEntityFactory
    {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @param IGroupEntity|null $group
     * @return IMatchEntityFactory
     */
    public function setGroup(?IGroupEntity $group): IMatchEntityFactory
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @param string|null $roundKey
     * @return IMatchEntityFactory
     */
    public function setRoundKey(?string $roundKey): IMatchEntityFactory
    {
        $this->roundKey = $roundKey;
        return $this;
    }

    /**
     * @param string|null $homeTeamId
     * @return IMatchEntityFactory
     */
    public function setHomeTeamId(?string $homeTeamId): IMatchEntityFactory
    {
        $this->homeTeamId = $homeTeamId;
        return $this;
    }

    /**
     * @param string|null $awayTeamId
     * @return IMatchEntityFactory
     */
    public function setAwayTeamId(?string $awayTeamId): IMatchEntityFactory
    {
        $this->awayTeamId = $awayTeamId;
        return $this;
    }

    /**
     * @param ITeamEntity|null $homeTeam
     * @return IMatchEntityFactory
     */
    public function setHomeTeam(?ITeamEntity $homeTeam): IMatchEntityFactory
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @param ITeamEntity|null $awayTeam
     * @return IMatchEntityFactory
     */
    public function setAwayTeam(?ITeamEntity $awayTeam): IMatchEntityFactory
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @param string|null $venueId
     * @return IMatchEntityFactory
     */
    public function setVenueId(?string $venueId): IMatchEntityFactory
    {
        $this->venueId = $venueId;
        return $this;
    }

    /**
     * @param IVenueEntity|null $venue
     * @return IMatchEntityFactory
     */
    public function setVenue(?IVenueEntity $venue): IMatchEntityFactory
    {
        $this->venue = $venue;
        return $this;
    }

    /**
     * @param MatchCoverage|null $coverage
     * @return IMatchEntityFactory
     */
    public function setCoverage(?MatchCoverage $coverage): IMatchEntityFactory
    {
        $this->coverage = $coverage;
        return $this;
    }

    /**
     * @param int|null $spectators
     * @return IMatchEntityFactory
     */
    public function setSpectators(?int $spectators): IMatchEntityFactory
    {
        $this->spectators = $spectators;
        return $this;
    }

    /**
     * @param IMatchRefereeEntity[]|null $referees
     * @return IMatchEntityFactory
     */
    public function setReferees(?array $referees): IMatchEntityFactory
    {
        $this->referees = $referees;
        return $this;
    }

    /**
     * @param IMatchScore|null $score
     * @return IMatchEntityFactory
     */
    public function setScore(?IMatchScore $score): IMatchEntityFactory
    {
        $this->score = $score;
        return $this;
    }

    public function setEmpty(): IMatchEntityFactory
    {
        return new MatchEntityFactory();
    }

    /**
     * @inheritDoc
     */
    public function setRoundTypeId(?string $roundTypeId): IMatchEntityFactory
    {
        $this->roundTypeId = $roundTypeId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setRound(?IRoundEntity $round): ?IMatchEntityFactory
    {
        $this->round = $round;
        return $this;
    }

    public function create(): IMatchEntity
    {
        return new MatchEntity(
            $this->id,
            $this->statusId,
            $this->status,
            $this->kickoffTime,
            $this->stageId,
            $this->stage,
            $this->groupId,
            $this->group,
            $this->roundKey,
            $this->homeTeamId,
            $this->awayTeamId,
            $this->homeTeam,
            $this->awayTeam,
            $this->venueId,
            $this->venue,
            $this->coverage,
            $this->spectators,
            $this->score,
            $this->referees,
            $this->phaseStartedAt,
            $this->finishedAt,
            $this->colors,
            $this->roundTypeId,
            $this->round
        );
    }

    /**
     * @param DateTimeInterface|null $phaseStartedAt
     * @return MatchEntityFactory
     */
    public function setPhaseStartedAt(?DateTimeInterface $phaseStartedAt): MatchEntityFactory
    {
        $this->phaseStartedAt = $phaseStartedAt;
        return $this;
    }

    /**
     * @param DateTimeInterface|null $finishedAt
     * @return MatchEntityFactory
     */
    public function setFinishedAt(?DateTimeInterface $finishedAt): MatchEntityFactory
    {
        $this->finishedAt = $finishedAt;
        return $this;
    }

    public function setColors(?ITeamColorsEntity $colors): IMatchEntityFactory
    {
        $this->colors = $colors;
        return $this;
    }

    public function setFrom(IMatchEntity $matchEntity): IMatchEntityFactory
    {
        $factory = new MatchEntityFactory();
        $factory->id = $matchEntity->getId();
        $factory->statusId = $matchEntity->getStatusId();
        $factory->status = $matchEntity->getStatus();
        $factory->kickoffTime = $matchEntity->getKickoffTime();
        $factory->stageId = $matchEntity->getStageId();
        $factory->stage = $matchEntity->getStage();
        $factory->groupId = $matchEntity->getGroupId();
        $factory->group = $matchEntity->getGroup();
        $factory->roundKey = $matchEntity->getRoundKey();
        $factory->homeTeamId = $matchEntity->getHomeTeamId();
        $factory->awayTeamId = $matchEntity->getAwayTeamId();
        $factory->homeTeam = $matchEntity->getHomeTeam();
        $factory->awayTeam = $matchEntity->getAwayTeam();
        $factory->venueId = $matchEntity->getVenueId();
        $factory->venue = $matchEntity->getVenue();
        $factory->coverage = $matchEntity->getCoverage();
        $factory->spectators = $matchEntity->getSpectators();
        $factory->score = $matchEntity->getScore();
        $factory->referees = $matchEntity->getReferees();
        $factory->phaseStartedAt = $matchEntity->getPhaseStartedAt();
        $factory->finishedAt = $matchEntity->getFinishedAt();
        $factory->colors = $matchEntity->getColors();
        $factory->roundTypeId = $matchEntity->getRoundTypeId();
        $factory->round = $matchEntity->getRound();
        return $factory;
    }
}