<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Input\Upsert;

use Sportal\FootballApi\Application\IDto;

class CollectionDto implements IDto
{
    /**
     * @var Dto[]
     */
    private array $playerStatisticDtoCollection;

    /**
     * @param Dto[] $playerStatisticDtoCollection
     */
    public function __construct(array $playerStatisticDtoCollection)
    {
        $this->playerStatisticDtoCollection = $playerStatisticDtoCollection;
    }

    /**
     * @return Dto[]
     */
    public function getPlayerStatisticDtoCollection(): array
    {
        return $this->playerStatisticDtoCollection;
    }
}