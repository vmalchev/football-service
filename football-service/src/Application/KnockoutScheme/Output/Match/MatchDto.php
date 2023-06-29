<?php


namespace Sportal\FootballApi\Application\KnockoutScheme\Output\Match;

use Sportal\FootballApi\Application\Match\Output\Get\Score\Dto as ScoreDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_KnockoutMatch")
 */
class MatchDto implements \JsonSerializable
{

    /**
     * @SWG\Property(property="id")
     * @var string|null
     */
    private ?string $id;

    /**
     * @SWG\Property(property="kickoff_time", format="date-time")
     * @var string
     */
    private string $kickoff_time;

    /**
     * @SWG\Property(property="score")
     * @var ScoreDto|null
     */
    private ?ScoreDto $score;

    /**
     * @SWG\Property(property="home_team_id")
     * @var string|null
     */
    private ?string $home_team_id;

    /**
     * @SWG\Property(property="away_team_id")
     * @var string|null
     */
    private ?string $away_team_id;

    /**
     * MatchDto constructor.
     * @param string|null $id
     * @param string $kickoff_time
     * @param ScoreDto|null $score
     * @param string|null $home_team_id
     * @param string|null $away_team_id
     */
    public function __construct(?string $id, string $kickoff_time, ?ScoreDto $score, ?string $home_team_id, ?string $away_team_id)
    {
        $this->id = $id;
        $this->kickoff_time = $kickoff_time;
        $this->score = $score;
        $this->home_team_id = $home_team_id;
        $this->away_team_id = $away_team_id;
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
    public function getKickoffTime(): string
    {
        return $this->kickoff_time;
    }

    /**
     * @return ScoreDto|null
     */
    public function getScore(): ?ScoreDto
    {
        return $this->score;
    }

    /**
     * @return string|null
     */
    public function getHomeTeamId(): ?string
    {
        return $this->home_team_id;
    }

    /**
     * @return string|null
     */
    public function getAwayTeamId(): ?string
    {
        return $this->away_team_id;
    }



    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}