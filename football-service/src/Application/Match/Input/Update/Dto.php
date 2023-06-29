<?php


namespace Sportal\FootballApi\Application\Match\Input\Update;

use App\Validation\Identifier;
use DateTimeInterface;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Domain\Match\MatchCoverage;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_MatchInput")
 */
class Dto implements IDto
{
    /**
     * @var string|null
     */
    private ?string $id;

    /**
     * @SWG\Property(property="status_id")
     * @var string
     */
    private string $status_id;

    /**
     * @SWG\Property(property="kickoff_time", format="date-time")
     * @var string
     */
    private string $kickoff_time;

    /**
     * @SWG\Property(property="stage_id")
     * @var string
     */
    private string $stage_id;

    /**
     * @SWG\Property(property="home_team_id")
     * @var string
     */
    private string $home_team_id;

    /**
     * @SWG\Property(property="away_team_id")
     * @var string
     */
    private string $away_team_id;

    /**
     * @SWG\Property(property="round_key")
     * @var string|null
     */
    private ?string $round_key;

    /**
     * @SWG\Property(property="group_id")
     * @var string|null
     */
    private ?string $group_id;

    /**
     * @SWG\Property(property="venue_id")
     * @var string|null
     */
    private ?string $venue_id;

    /**
     * @SWG\Property(property="referees")
     * @var Referee\Dto[]|null
     */
    private ?array $referees;

    /**
     * @SWG\Property(property="score")
     * @var Score\Dto|null
     */
    private ?Score\Dto $score;

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
     * @SWG\Property(property="finished_at", format="date-time")
     * @var string|null
     */
    private ?string $finished_at;

    /**
     * @SWG\Property(property="phase_started_at", format="date-time")
     * @var string|null
     */
    private ?string $phase_started_at;

    /**
     * Dto constructor.
     * @param string $status_id
     * @param string $kickoff_time
     * @param string $stage_id
     * @param string $home_team_id
     * @param string $away_team_id
     * @param string|null $id
     * @param string|null $round_key
     * @param string|null $group_id
     * @param string|null $venue_id
     * @param Referee\Dto[]|null $referees
     * @param Score\Dto|null $score
     * @param int|null $spectators
     * @param string|null $coverage
     * @param string|null $finished_at
     * @param string|null $phase_started_at
     */
    public function __construct(string $status_id,
                                string $kickoff_time,
                                string $stage_id,
                                string $home_team_id,
                                string $away_team_id,
                                ?string $id = null,
                                ?string $round_key = null,
                                ?string $group_id = null,
                                ?string $venue_id = null,
                                ?array $referees = null,
                                ?Score\Dto $score = null,
                                ?int $spectators = null,
                                ?string $coverage = null,
                                ?string $finished_at = null,
                                ?string $phase_started_at = null)
    {
        $this->id = $id;
        $this->status_id = $status_id;
        $this->kickoff_time = $kickoff_time;
        $this->stage_id = $stage_id;
        $this->home_team_id = $home_team_id;
        $this->away_team_id = $away_team_id;
        $this->round_key = $round_key;
        $this->group_id = $group_id;
        $this->venue_id = $venue_id;
        $this->referees = $referees;
        $this->score = $score;
        $this->spectators = $spectators;
        $this->coverage = $coverage;
        $this->finished_at = $finished_at;
        $this->phase_started_at = $phase_started_at;
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
        return $this->status_id;
    }

    /**
     * @return string
     */
    public function getKickoffTime(): string
    {
        return $this->kickoff_time;
    }

    /**
     * @return string
     */
    public function getStageId(): string
    {
        return $this->stage_id;
    }

    /**
     * @return string
     */
    public function getHomeTeamId(): string
    {
        return $this->home_team_id;
    }

    /**
     * @return string
     */
    public function getAwayTeamId(): string
    {
        return $this->away_team_id;
    }

    /**
     * @return string|null
     */
    public function getRoundKey(): ?string
    {
        return $this->round_key;
    }

    /**
     * @return string|null
     */
    public function getGroupId(): ?string
    {
        return $this->group_id;
    }

    /**
     * @return string|null
     */
    public function getVenueId(): ?string
    {
        return $this->venue_id;
    }

    /**
     * @return Referee\Dto[]|null
     */
    public function getReferees(): ?array
    {
        return $this->referees;
    }

    /**
     * @return Score\Dto|null
     */
    public function getScore(): ?Score\Dto
    {
        return $this->score;
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
     * @return string|null
     */
    public function getFinishedAt(): ?string
    {
        return $this->finished_at;
    }

    /**
     * @return string|null
     */
    public function getPhaseStartedAt(): ?string
    {
        return $this->phase_started_at;
    }

    /**
     * @param string|null $id
     * @return Dto
     */
    public function setId(?string $id): Dto
    {
        $dto = clone $this;
        $dto->id = $id;
        return $dto;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection([
            'status_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'stage_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'kickoff_time' => [
                new Assert\NotBlank(),
                new Assert\DateTime(DateTimeInterface::ATOM)
            ],
            'home_team_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'away_team_id' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ],
            'round_key' => new Assert\Optional([
                new Assert\Length(['max' => 15])
            ]),
            'group_id' => new Assert\Optional([
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]),
            'venue_id' => new Assert\Optional([
                new Assert\Type(['type' => ['digit', 'numeric']]),
                new Identifier(),
            ]),
            'referees' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All(Referee\Dto::getValidatorConstraints())
            ]),
            'score' => new Assert\Optional(Score\Dto::getValidatorConstraints()),
            'coverage' => new Assert\Optional(new Assert\Choice([
                'choices' => array_values(MatchCoverage::keys()),
                'message' => 'Choose a valid position. Options are: ' . implode(", ", MatchCoverage::keys())
            ])),
            'finished_at' => new Assert\Optional(new Assert\DateTime(DateTimeInterface::ATOM)),
            'phase_started_at' => new Assert\Optional(new Assert\DateTime(DateTimeInterface::ATOM)),
            'spectators' => new Assert\Optional([
                new Assert\Type('int'),
                new Assert\GreaterThanOrEqual(0)
            ])
        ]);
    }
}