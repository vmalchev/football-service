<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;

use DateTimeInterface;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\TeamSquad\ITeamCoachEntity;
use Sportal\FootballApi\Domain\TeamSquad\TeamSquadStatus;
use Sportal\FootballApi\Infrastructure\Database\GeneratedIdDatabaseEntity;

class TeamCoachEntity extends GeneratedIdDatabaseEntity implements ITeamCoachEntity
{
    private ?string $id;
    private string $teamId;
    private string $coachId;
    private TeamSquadStatus $status;
    private ?ICoachEntity $coach;
    private ?DateTimeInterface $startDate;
    private ?DateTimeInterface $endDate;

    /**
     * TeamCoachEntity constructor.
     * @param string|null $id
     * @param string $teamId
     * @param string $coachId
     * @param TeamSquadStatus $status
     * @param ICoachEntity|null $coach
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     */
    public function __construct(?string $id, string $teamId, string $coachId, TeamSquadStatus $status, ?ICoachEntity $coach, ?DateTimeInterface $startDate, ?DateTimeInterface $endDate)
    {
        $this->id = $id;
        $this->teamId = $teamId;
        $this->coachId = $coachId;
        $this->status = $status;
        $this->coach = $coach;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function withId(string $id): GeneratedIdDatabaseEntity
    {
        $updated = clone $this;
        $updated->id = $id;
        return $updated;
    }

    public function getDatabaseEntry(): array
    {
        return [
            TeamCoachTableMapper::FIELD_TEAM_ID => $this->getTeamId(),
            TeamCoachTableMapper::FIELD_COACH_ID => $this->getCoachId(),
            TeamCoachTableMapper::FIELD_START_DATE => !is_null($this->getStartDate()) ? $this->getStartDate()->format("Y-m-d") : null,
            TeamCoachTableMapper::FIELD_END_DATE => !is_null($this->getEndDate()) ? $this->getEndDate()->format("Y-m-d") : null,
            TeamCoachTableMapper::FIELD_ACTIVE => StatusDatabaseConverter::toValue($this->status),
        ];
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
    public function getCoachId(): string
    {
        return $this->coachId;
    }

    /**
     * @return TeamSquadStatus
     */
    public function getStatus(): TeamSquadStatus
    {
        return $this->status;
    }

    /**
     * @return ICoachEntity|null
     */
    public function getCoach(): ?ICoachEntity
    {
        return $this->coach;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }


}