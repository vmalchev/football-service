<?php
namespace Sportal\FootballApi\Application\PlayerStatistic\Input\Upsert;


use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;

class CollectionMapper
{
    private Mapper $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param CollectionDto $playerStatisticCollectionDto
     * @return IPlayerStatisticEntity[]
     */
    public function map(CollectionDto $playerStatisticCollectionDto): array
    {
        $playerStatisticEntityCollection = [];
        foreach ($playerStatisticCollectionDto->getPlayerStatisticDtoCollection() as $playerStatisticDto) {
            $playerStatisticEntityCollection[] = $this->mapper->map($playerStatisticDto);
        }

        return $playerStatisticEntityCollection;
    }
}