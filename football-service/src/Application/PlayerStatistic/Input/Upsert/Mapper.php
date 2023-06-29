<?php


namespace Sportal\FootballApi\Application\PlayerStatistic\Input\Upsert;


use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntityFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItemFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\OriginType;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerStatisticType;
use Sportal\FootballApi\Infrastructure\PlayerStatistic\PlayerStatisticItem;

class Mapper
{
    private IPlayerStatisticEntityFactory $playerStatisticEntityFactory;
    private IPlayerStatisticItemFactory $playerStatisticItemFactory;

    /**
     * @param IPlayerStatisticEntityFactory $playerStatisticEntityFactory
     * @param IPlayerStatisticItemFactory $playerStatisticItemFactory
     */
    public function __construct(
        IPlayerStatisticEntityFactory $playerStatisticEntityFactory,
        IPlayerStatisticItemFactory $playerStatisticItemFactory
    ) {
        $this->playerStatisticEntityFactory = $playerStatisticEntityFactory;
        $this->playerStatisticItemFactory = $playerStatisticItemFactory;
    }

    public function map(Dto $dto): IPlayerStatisticEntity
    {
        return $this->playerStatisticEntityFactory
            ->setMatchId($dto->getMatchId())
            ->setPlayerId($dto->getPlayerId())
            ->setTeamId($dto->getTeamId())
            ->setStatistics(
                array_map(
                    function ($statisticItem) {
                        return $this->playerStatisticItemFactory
                            ->setName(new PlayerStatisticType($statisticItem->getName()))
                            ->setValue($statisticItem->getValue())
                            ->create();
                    }, $dto->getStatistics()
                )
            )
            ->setOrigin(OriginType::forKey($dto->getOrigin()))
            ->create();
    }
}