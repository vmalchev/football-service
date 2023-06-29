<?php


namespace Sportal\FootballApi\Infrastructure\Lineup;


use DateTime;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Lineup\ILineupEntity;
use Sportal\FootballApi\Domain\Lineup\LineupStatus;
use Sportal\FootballApi\Infrastructure\Database\IDatabaseEntity;

class LineupEntity implements ILineupEntity, IDatabaseEntity
{
    /**
     * @var string
     */
    private string $matchId;

    /**
     * @var LineupStatus|null
     */
    private ?LineupStatus $status;

    /**
     * @var string|null
     */
    private ?string $homeTeamFormation;

    /**
     * @var string|null
     */
    private ?string $awayTeamFormation;

    /**
     * @var ICoachEntity|null
     */
    private ?ICoachEntity $homeCoach;

    /**
     * @var string|null
     */
    private ?string $homeCoachId;

    /**
     * @var ICoachEntity|null
     */
    private ?ICoachEntity $awayCoach;

    /**
     * @var string|null
     */
    private ?string $awayCoachId;

    /**
     * @var string|null
     */
    private ?string $homeTeamId;

    /**
     * @var string|null
     */
    private ?string $awayTeamId;

    /**
     * LineupEntity constructor.
     * @param string $matchId
     * @param LineupStatus|null $status
     * @param string|null $homeTeamFormation
     * @param string|null $awayTeamFormation
     * @param string|null $homeCoachId
     * @param string|null $awayCoachId
     * @param ICoachEntity|null $homeCoach
     * @param ICoachEntity|null $awayCoach
     * @param string|null $homeTeamId
     * @param string|null $awayTeamId
     */
    public function __construct(string $matchId,
                                ?LineupStatus $status,
                                ?string $homeTeamFormation,
                                ?string $awayTeamFormation,
                                ?string $homeCoachId,
                                ?string $awayCoachId,
                                ?ICoachEntity $homeCoach,
                                ?ICoachEntity $awayCoach,
                                ?string $homeTeamId,
                                ?string $awayTeamId)
    {
        $this->matchId = $matchId;
        $this->status = $status;
        $this->homeTeamFormation = $homeTeamFormation;
        $this->awayTeamFormation = $awayTeamFormation;
        $this->homeCoachId = $homeCoachId;
        $this->awayCoachId = $awayCoachId;
        $this->homeCoach = $homeCoach;
        $this->awayCoach = $awayCoach;
        $this->homeTeamId = $homeTeamId;
        $this->awayTeamId = $awayTeamId;
    }

    /**
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->matchId;
    }

    /**
     * @return LineupStatus
     */
    public function getStatus(): ?LineupStatus
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getHomeTeamFormation(): ?string
    {
        return $this->homeTeamFormation;
    }

    /**
     * @return string|null
     */
    public function getAwayTeamFormation(): ?string
    {
        return $this->awayTeamFormation;
    }

    /**
     * @return ICoachEntity|null
     */
    public function getHomeCoach(): ?ICoachEntity
    {
        return $this->homeCoach;
    }

    /**
     * @return ICoachEntity|null
     */
    public function getAwayCoach(): ?ICoachEntity
    {
        return $this->awayCoach;
    }

    public function getPrimaryKey(): array
    {
        return [LineupTable::FIELD_MATCH_ID => $this->matchId];
    }

    /**
     * @return string|null
     */
    public function getHomeCoachId(): ?string
    {
        return $this->homeCoachId;
    }

    /**
     * @return string|null
     */
    public function getAwayCoachId(): ?string
    {
        return $this->awayCoachId;
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

    public function getDatabaseEntry(): array
    {
        return [
            LineupTable::FIELD_MATCH_ID => $this->getMatchId(),
            LineupTable::FIELD_AWAY_COACH_ID => $this->getAwayCoachId(),
            LineupTable::FIELD_HOME_COACH_ID => $this->getHomeCoachId(),
            LineupTable::FIELD_CONFIRMED => $this->getStatus()->getValue(),
            LineupTable::FIELD_HOME_FORMATION => $this->getHomeTeamFormation(),
            LineupTable::FIELD_AWAY_FORMATION => $this->getAwayTeamFormation(),
            LineupTable::FIELD_UPDATED_AT => new DateTime()
        ];
    }
}