<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Upsert;

use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;


/**
 * @SWG\Definition(definition="v2_PlayerStatisticOutput")
 */
class Dto implements \JsonSerializable, IDto
{
    /**
     * @var string
     * @SWG\Property(property="player_id")
     */
    private string $player_id;

    /**
     * @var string
     * @SWG\Property(property="match_id")
     */
    private string $match_id;

    /**
     * @var string
     * @SWG\Property(property="team_id")
     */
    private string $team_id;

    /**
     * @SWG\Property(property="statistics")
     */
    private array $statistics;

    /**
     * @var string
     * @SWG\Property(property="origin")
     */
    private string $origin;

    /**
     * @param string $player_id
     * @param string $match_id
     * @param string $team_id
     * @param array $statistics
     * @param string $origin
     */
    public function __construct(string $player_id, string $match_id, string $team_id, array $statistics, string $origin)
    {
        $this->player_id = $player_id;
        $this->match_id = $match_id;
        $this->team_id = $team_id;
        $this->statistics = $statistics;
        $this->origin = $origin;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}