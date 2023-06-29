<?php


namespace Sportal\FootballApi\Application\Match\Output\Get;


use JsonSerializable;
use Sportal\FootballApi\Application\Group;
use Sportal\FootballApi\Application\IEventNotificationable;
use Sportal\FootballApi\Application\KnockoutScheme;
use Sportal\FootballApi\Application\MatchStatus;
use Sportal\FootballApi\Application\Season;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_Match")
 */
class Dto implements JsonSerializable, IEventNotificationable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    private string $id;

    /**
     * @SWG\Property(property="status")
     * @var MatchStatus\Output\Get\Dto
     */
    private MatchStatus\Output\Get\Dto $status;

    /**
     * @SWG\Property(property="kickoff_time", format="date-time")
     * @var string
     */
    private string $kickoff_time;

    /**
     * @SWG\Property(property="stage")
     * @var \Sportal\FootballApi\Application\Stage\Output\Get\Dto
     */
    private \Sportal\FootballApi\Application\Stage\Output\Get\Dto $stage;

    /**
     * @SWG\Property(property="season")
     * @var Season\Output\Get\Dto
     */
    private Season\Output\Get\Dto $season;

    /**
     * @SWG\Property(property="group")
     * @var Group\Output\Get\Dto|null
     */
    private ?Group\Output\Get\Dto $group;

    /**
     * @SWG\Property(property="round")
     * @var \Sportal\FootballApi\Application\Round\Output\Partial\Dto|null
     */
    private ?\Sportal\FootballApi\Application\Round\Output\Partial\Dto $round;

    /**
     * @SWG\Property(property="home_team")
     * @var Team\Dto|null
     */
    private ?Team\Dto $home_team;

    /**
     * @SWG\Property(property="away_team")
     * @var Team\Dto|null
     */
    private ?Team\Dto $away_team;

    /**
     * @SWG\Property(property="referees")
     * @var Referee\Dto[]
     */
    private ?array $referees;

    /**
     * @SWG\Property(property="venue")
     * @var Venue\Dto|null
     */
    private ?Venue\Dto $venue;

    /**
     * @SWG\Property(property="spectators")
     * @var int|null
     */
    private ?int $spectators;

    /**
     * @SWG\Property(property="coverage", enum=MATCH_COVERAGE)
     * @var string|null
     */
    private ?string $coverage;

    /**
     * @SWG\Property(property="minute")
     * @var Minute\Dto|null
     */
    private ?Minute\Dto $minute;

    /**
     * @SWG\Property(property="phase_started_at", format="date-time")
     * @var string|null
     */
    private ?string $phase_started_at;

    /**
     * @SWG\Property(property="finished_at", format="date-time")
     * @var string|null
     */
    private ?string $finished_at;

    /**
     * @SWG\Property(property="score")
     * @var Score\Dto|null
     */
    private ?Score\Dto $score;

    /**
     * Dto constructor.
     * @param string $id
     * @param MatchStatus\Output\Get\Dto $status
     * @param string $kickoff_time
     * @param \Sportal\FootballApi\Application\Stage\Output\Get\Dto $stage
     * @param Season\Output\Get\Dto $season
     * @param Group\Output\Get\Dto|null $group
     * @param \Sportal\FootballApi\Application\Round\Output\Partial\Dto|null $round
     * @param Team\Dto|null $home_team
     * @param Team\Dto|null $away_team
     * @param Referee\Dto[]|null $referees
     * @param Venue\Dto|null $venue
     * @param int|null $spectators
     * @param string|null $coverage
     * @param Minute\Dto|null $minute
     * @param string|null $phase_started_at
     * @param string|null $finished_at
     * @param Score\Dto|null $score
     */
    public function __construct(string                                                     $id,
                                MatchStatus\Output\Get\Dto                                 $status,
                                string                                                     $kickoff_time,
                                \Sportal\FootballApi\Application\Stage\Output\Get\Dto      $stage,
                                Season\Output\Get\Dto                                      $season,
                                ?Group\Output\Get\Dto                                      $group,
                                ?\Sportal\FootballApi\Application\Round\Output\Partial\Dto $round,
                                ?Team\Dto                                                  $home_team,
                                ?Team\Dto                                                  $away_team,
                                ?array                                                     $referees,
                                ?Venue\Dto                                                 $venue,
                                ?int                                                       $spectators,
                                ?string                                                    $coverage,
                                ?Minute\Dto                                                $minute,
                                ?string                                                    $phase_started_at,
                                ?string                                                    $finished_at,
                                ?Score\Dto                                                 $score)
    {
        $this->id = $id;
        $this->status = $status;
        $this->kickoff_time = $kickoff_time;
        $this->stage = $stage;
        $this->season = $season;
        $this->group = $group;
        $this->round = $round;
        $this->home_team = $home_team;
        $this->away_team = $away_team;
        $this->referees = $referees;
        $this->venue = $venue;
        $this->spectators = $spectators;
        $this->coverage = $coverage;
        $this->minute = $minute;
        $this->phase_started_at = $phase_started_at;
        $this->finished_at = $finished_at;
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return MatchStatus\Output\Get\Dto
     */
    public function getStatus(): MatchStatus\Output\Get\Dto
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getKickoffTime(): string
    {
        return $this->kickoff_time;
    }

    /**
     * @return \Sportal\FootballApi\Application\Stage\Output\Get\Dto
     */
    public function getStage(): \Sportal\FootballApi\Application\Stage\Output\Get\Dto
    {
        return $this->stage;
    }

    /**
     * @return Season\Output\Get\Dto
     */
    public function getSeason(): Season\Output\Get\Dto
    {
        return $this->season;
    }

    /**
     * @return Group\Output\Get\Dto|null
     */
    public function getGroup(): ?Group\Output\Get\Dto
    {
        return $this->group;
    }

    /**
     * @return \Sportal\FootballApi\Application\Round\Output\Partial\Dto|null
     */
    public function getRound(): ?\Sportal\FootballApi\Application\Round\Output\Partial\Dto
    {
        return $this->round;
    }

    /**
     * @return Team\Dto|null
     */
    public function getHomeTeam(): ?Team\Dto
    {
        return $this->home_team;
    }

    /**
     * @return Team\Dto|null
     */
    public function getAwayTeam(): ?Team\Dto
    {
        return $this->away_team;
    }

    /**
     * @return Referee\Dto[]
     */
    public function getReferees(): ?array
    {
        return $this->referees;
    }

    /**
     * @return Venue\Dto|null
     */
    public function getVenue(): ?Venue\Dto
    {
        return $this->venue;
    }

    /**
     * @return int|null
     */
    public function getSpectators(): ?int
    {
        return $this->spectators;
    }

    /**
     * @return string|null
     */
    public function getCoverage(): ?string
    {
        return $this->coverage;
    }

    /**
     * @return Dto|null
     */
    public function getMinute(): ?Dto
    {
        return $this->minute;
    }

    /**
     * @return string|null
     */
    public function getPhaseStartedAt(): ?string
    {
        return $this->phase_started_at;
    }

    /**
     * @return string|null
     */
    public function getFinishedAt(): ?string
    {
        return $this->finished_at;
    }

    /**
     * @return Score\Dto|null
     */
    public function getScore(): ?Score\Dto
    {
        return $this->score;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}