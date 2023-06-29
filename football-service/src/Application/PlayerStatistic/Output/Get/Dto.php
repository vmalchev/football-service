<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Get;

use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Infrastructure\PlayerStatistic\PlayerStatisticItem;
use Swagger\Annotations as SWG;


/**
 * @SWG\Definition(definition="v2_PlayerSeasonStatisticOutput")
 */
class Dto implements \JsonSerializable, IDto
{
    /**
     * @var \Sportal\FootballApi\Application\Player\Output\Get\Dto
     * @SWG\Property(property="player")
     */
    private \Sportal\FootballApi\Application\Player\Output\Get\Dto $player;

    /**
     * @SWG\Property(property="teams")
     * @var \Sportal\FootballApi\Application\Team\Output\Get\Dto[]
     */
    private array $teams;

    /**
     * @var \Sportal\FootballApi\Application\Season\Output\Get\Dto
     * @SWG\Property(property="season")
     */
    private \Sportal\FootballApi\Application\Season\Output\Get\Dto $season;

    /**
     * @SWG\Property(property="statistics")
     * @var StatisticItemDto[]
     */
    private array $statistics;

    /**
     * @param array $teams
     * @param array $statistics
     * @param \Sportal\FootballApi\Application\Season\Output\Get\Dto $season
     * @param \Sportal\FootballApi\Application\Player\Output\Get\Dto $playerDto
     */
    public function __construct( array $teams,
                                 array $statistics,
                                 \Sportal\FootballApi\Application\Season\Output\Get\Dto $season,
                                 \Sportal\FootballApi\Application\Player\Output\Get\Dto $playerDto)
    {
        $this->teams = $teams;
        $this->statistics = $statistics;
        $this->season = $season;
        $this->player = $playerDto;
    }


    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}