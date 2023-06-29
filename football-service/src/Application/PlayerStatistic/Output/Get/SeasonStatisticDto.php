<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Get;


use Sportal\FootballApi\Application\IDto;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="v2_PlayerSeasonStatisticOutputCollection")
 */
class SeasonStatisticDto implements IDto, \JsonSerializable
{

    /**
     * @SWG\Property(property="statistics")
     * @var Dto[]
     */
    private array $statistics;

    public function __construct($statistics)
    {
        $this->statistics = $statistics;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}