<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use DateTimeInterface;
use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Match\IMatchRefereeEntity;
use Sportal\FootballApi\Domain\Match\IMatchScore;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Sportal\FootballApi\Domain\Match\MatchRefereeRole;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamEntity;
use Sportal\FootballApi\Domain\Venue\IVenueEntity;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchCoverageConverter;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchScoreConverter;

class MatchEntity extends GeneratedIdDatabaseEntity implements IMatchEntity, IDatabaseEntity
{
    private ?string $id;
    private string $statusId;
    private ?IMatchStatusEntity $status;
    private DateTimeInterface $kickoffTime;
    private string $stageId;
    private ?IStageEntity $stage;
    private ?string $groupId;
    private ?IGroupEntity $group;
    private ?string $roundKey;
    private ?string $homeTeamId;
    private ?string $awayTeamId;
    private ?ITeamEntity $homeTeam;
    private ?ITeamEntity $awayTeam;
    private ?string $venueId;
    private ?IVenueEntity $venue;
    private ?MatchCoverage $coverage;
    private ?int $spectators;
    private ?IMatchScore $score;
    private ?array $referees;
    private ?DateTimeInterface $phaseStartedAt;
    private ?DateTimeInterface $finishedAt;
    private ?ITeamColorsEntity $colors;
    private ?string $roundTypeId;
    private ?IRoundEntity $round;

    /**
     * MatchEntity constructor.
     * @param string|null $id
     * @param string $statusId
     * @param IMatchStatusEntity|null $status
     * @param DateTimeInterface $kickoffTime
     * @param string $stageId
     * @param IStageEntity|null $stage
     * @param string|null $groupId
     * @param IGroupEntity|null $group
     * @param string|null $roundKey
     * @param string|null $homeTeamId
     * @param string|null $awayTeamId
     * @param ITeamEntity|null $homeTeam
     * @param ITeamEntity|null $awayTeam
     * @param string|null $venueId
     * @param IVenueEntity|null $venue
     * @param MatchCoverage|null $coverage
     * @param int|null $spectators
     * @param IMatchScore|null $score
     * @param array|null $referees
     * @param DateTimeInterface|null $phaseStartedAt
     * @param DateTimeInterface|null $finishedAt
     * @param ITeamColorsEntity|null $colors
     * @param string|null $roundTypeId
     * @param IRoundEntity|null $roundEntity
     */
    public function __construct(?string $id,
                                string $statusId,
                                ?IMatchStatusEntity $status,
                                DateTimeInterface $kickoffTime,
                                string $stageId,
                                ?IStageEntity $stage,
                                ?string $groupId,
                                ?IGroupEntity $group,
                                ?string $roundKey,
                                ?string $homeTeamId,
                                ?string $awayTeamId,
                                ?ITeamEntity $homeTeam,
                                ?ITeamEntity $awayTeam,
                                ?string $venueId,
                                ?IVenueEntity $venue,
                                ?MatchCoverage $coverage,
                                ?int $spectators,
                                ?IMatchScore $score,
                                ?array $referees,
                                ?DateTimeInterface $phaseStartedAt,
                                ?DateTimeInterface $finishedAt,
                                ?ITeamColorsEntity $colors,
                                ?string $roundTypeId,
                                ?IRoundEntity $roundEntity)
    {
        $this->id = $id;
        $this->statusId = $statusId;
        $this->status = $status;
        $this->kickoffTime = $kickoffTime;
        $this->stageId = $stageId;
        $this->stage = $stage;
        $this->groupId = $groupId;
        $this->group = $group;
        $this->roundKey = $roundKey;
        $this->homeTeamId = $homeTeamId;
        $this->awayTeamId = $awayTeamId;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->venueId = $venueId;
        $this->venue = $venue;
        $this->coverage = $coverage;
        $this->spectators = $spectators;
        $this->score = $score;
        $this->referees = $referees;
        $this->phaseStartedAt = $phaseStartedAt;
        $this->finishedAt = $finishedAt;
        $this->colors = $colors;
        $this->roundTypeId = $roundTypeId;
        $this->round = $roundEntity;
    }


    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStatusId(): string
    {
        return $this->statusId;
    }

    /**
     * @return IMatchStatusEntity|null
     */
    public function getStatus(): ?IMatchStatusEntity
    {
        return $this->status;
    }

    /**
     * @return DateTimeInterface
     */
    public function getKickoffTime(): DateTimeInterface
    {
        return $this->kickoffTime;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stageId;
    }

    /**
     * @return IStageEntity|null
     */
    public function getStage(): ?IStageEntity
    {
        return $this->stage;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->groupId;
    }

    /**
     * @return IGroupEntity|null
     */
    public function getGroup(): ?IGroupEntity
    {
        return $this->group;
    }

    /**
     * @return string|null
     */
    public function getRoundKey(): ?string
    {
        return $this->roundKey;
    }

    /**
     * @return string|null
     */
    public function getHomeTeamId(): ?string
    {
        return $this->homeTeamId;
    }

    /**
     * @return string|null
     */
    public function getAwayTeamId(): ?string
    {
        return $this->awayTeamId;
    }

    /**
     * @return ITeamEntity|null
     */
    public function getHomeTeam(): ?ITeamEntity
    {
        return $this->homeTeam;
    }

    /**
     * @return ITeamEntity|null
     */
    public function getAwayTeam(): ?ITeamEntity
    {
        return $this->awayTeam;
    }

    /**
     * @return string|null
     */
    public function getVenueId(): ?string
    {
        return $this->venueId;
    }

    /**
     * @return IVenueEntity|null
     */
    public function getVenue(): ?IVenueEntity
    {
        return $this->venue;
    }

    /**
     * @return MatchCoverage|null
     */
    public function getCoverage(): ?MatchCoverage
    {
        return $this->coverage;
    }

    /**
     * @return int|null
     */
    public function getSpectators(): ?int
    {
        return $this->spectators;
    }

    /**
     * @return IMatchScore|null
     */
    public function getScore(): ?IMatchScore
    {
        return $this->score;
    }

    /**
     * @return IMatchRefereeEntity[]|null
     */
    public function getReferees(): ?array
    {
        return $this->referees;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPhaseStartedAt(): ?DateTimeInterface
    {
        return $this->phaseStartedAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getFinishedAt(): ?DateTimeInterface
    {
        return $this->finishedAt;
    }

    /**
     * @return ITeamColorsEntity|null
     */
    public function getColors(): ?ITeamColorsEntity
    {
        return $this->colors;
    }

    /**
     * @inheritDoc
     */
    public function getRoundTypeId(): ?string
    {
        return $this->roundTypeId;
    }

    /**
     * @inheritDoc
     */
    public function getRound(): ?IRoundEntity
    {
        return $this->round;
    }

    /**
     * @inheritDoc
     */
    public function getDatabaseEntry(): array
    {
        $refereeId = null;
        if (!empty($this->referees)) {
            $mainReferees = array_filter($this->referees, fn($refereeEntity) => $refereeEntity->getRole()->equals(MatchRefereeRole::REFEREE()));
            $refereeId = isset($mainReferees[0]) ? $mainReferees[0]->getRefereeId() : null;
        }
        $data = [
            MatchTableMapper::FIELD_KICKOFF_TIME => $this->getKickoffTime(),
            MatchTableMapper::FIELD_STATUS_ID => $this->getStatusId(),
            MatchTableMapper::FIELD_STAGE_ID => $this->getStageId(),
            MatchTableMapper::FIELD_GROUP_ID => $this->getGroupId(),
            MatchTableMapper::FIELD_ROUND_KEY => $this->getRoundKey(),
            MatchTableMapper::FIELD_HOME_TEAM_ID => $this->getHomeTeamId(),
            MatchTableMapper::FIELD_AWAY_TEAM_ID => $this->getAwayTeamId(),
            MatchTableMapper::FIELD_VENUE_ID => $this->getVenueId(),
            MatchTableMapper::FIELD_REFEREE_ID => $refereeId,
            MatchTableMapper::FIELD_SPECTATORS => $this->getSpectators(),
            MatchTableMapper::FIELD_COVERAGE => MatchCoverageConverter::toValue($this->getCoverage()),
            MatchTableMapper::FIELD_PHASE_STARTED_AT => $this->getPhaseStartedAt(),
            MatchTableMapper::FIELD_FINISHED_AT => $this->getFinishedAt(),
            MatchTableMapper::FIELD_HOME_TEAM_NAME => $this->homeTeam !== null ? $this->homeTeam->getName() : null,
            MatchTableMapper::FIELD_AWAY_TEAM_NAME => $this->awayTeam !== null ? $this->awayTeam->getName() : null,
            MatchTableMapper::FIELD_ROUND_TYPE_ID => $this->getRoundTypeId()
        ];
        return array_merge($data, MatchScoreConverter::toValue($this->score));
    }

    /**
     * @inheritDoc
     */
    public function withId(string $id): MatchEntity
    {
        $entity = clone $this;
        $entity->id = $id;
        return $entity;
    }
}