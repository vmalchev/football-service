<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Upsert;


use Sportal\FootballApi\Application\IDto;


class CollectionDto implements \JsonSerializable, IDto
{
    /**
     * @var Dto[]
     */
    private array $statistics;

    /**
     * @param Dto[] $playerStatistics
     */
    public function __construct(array $playerStatistics)
    {
        $this->statistics = $playerStatistics;
    }

    /**
     * @return Dto[]
     */
    public function getPlayerStatistics(): array
    {
        return $this->statistics;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}