<?php


namespace Sportal\FootballApi\Application\MatchEvent\Output\Get;

use JsonSerializable;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\Match\Output\Get\Score\TeamScoreDto;
use Sportal\FootballApi\Application\Player;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_MatchEvent")
 */
class Dto implements IDto, JsonSerializable
{
    /**
     * @SWG\Property(property="id")
     * @var string
     */
    private string $id;

    /**
     * @SWG\Property(property="match_id")
     * @var string
     */
    private string $match_id;


    /**
     * @SWG\Property(enum=MATCH_EVENT_TYPE, property="type_code")
     * @var string
     */
    private string $type_code;


    /**
     * @SWG\Property(enum=TEAM_POSITION_STATUS, property="team_position")
     * @var string
     */
    private string $team_position;


    /**
     * @SWG\Property(property="minute")
     * @var int
     */
    private int $minute;

    /**
     * @SWG\Property(property="team_id")
     * @var string|null
     */
    private ?string $team_id;

    /**
     * @SWG\Property(property="primary_player")
     * @var Player\Output\Get\Dto|null
     */
    private ?Player\Output\Get\Dto $primary_player;


    /**
     * @SWG\Property(property="secondary_player")
     * @var Player\Output\Get\Dto|null
     */
    private ?Player\Output\Get\Dto $secondary_player;


    /**
     * @SWG\Property(property="score")
     * @var TeamScoreDto|null
     */
    private ?TeamScoreDto $score;

    /**
     * Dto constructor.
     * @param string $id
     * @param string $match_id
     * @param string $type_code
     * @param string $team_position
     * @param int $minute
     * @param string|null $team_id
     * @param Player\Output\Get\Dto|null $primary_player
     * @param Player\Output\Get\Dto|null $secondary_player
     * @param TeamScoreDto|null $score
     */
    public function __construct(string $id,
                                string $match_id,
                                string $type_code,
                                string $team_position,
                                int $minute,
                                ?string $team_id,
                                ?Player\Output\Get\Dto $primary_player,
                                ?Player\Output\Get\Dto $secondary_player,
                                ?TeamScoreDto $score)
    {
        $this->id = $id;
        $this->match_id = $match_id;
        $this->type_code = $type_code;
        $this->team_position = $team_position;
        $this->minute = $minute;
        $this->team_id = $team_id;
        $this->primary_player = $primary_player;
        $this->secondary_player = $secondary_player;
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
     * @return string
     */
    public function getMatchId(): string
    {
        return $this->match_id;
    }

    /**
     * @return string
     */
    public function getTypeCode(): string
    {
        return $this->type_code;
    }

    /**
     * @return string
     */
    public function getTeamPosition(): string
    {
        return $this->team_position;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * @return string|null
     */
    public function getTeamId(): ?string
    {
        return $this->team_id;
    }

    /**
     * @return Player\Output\Get\Dto|null
     */
    public function getPrimaryPlayer(): ?Player\Output\Get\Dto
    {
        return $this->primary_player;
    }

    /**
     * @return Player\Output\Get\Dto|null
     */
    public function getSecondaryPlayer(): ?Player\Output\Get\Dto
    {
        return $this->secondary_player;
    }

    /**
     * @return TeamScoreDto|null
     */
    public function getScore(): ?TeamScoreDto
    {
        return $this->score;
    }


    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}