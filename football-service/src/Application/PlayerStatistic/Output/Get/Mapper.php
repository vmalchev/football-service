<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Get;

use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticEntity;

class Mapper
{

    private \Sportal\FootballApi\Application\Player\Output\Get\Mapper $playerMapper;
    private \Sportal\FootballApi\Application\Team\Output\Get\Mapper $teamMapper;
    private \Sportal\FootballApi\Application\Season\Output\Get\Mapper $seasonMapper;

    /**
     * Mapper constructor.
     * @param \Sportal\FootballApi\Application\Player\Output\Get\Mapper $playerMapper
     * @param \Sportal\FootballApi\Application\Team\Output\Get\Mapper $teamMapper
     * @param \Sportal\FootballApi\Application\Season\Output\Get\Mapper $seasonMapper
     */
    public function __construct(\Sportal\FootballApi\Application\Player\Output\Get\Mapper $playerMapper,
                                \Sportal\FootballApi\Application\Team\Output\Get\Mapper $teamMapper,
                                \Sportal\FootballApi\Application\Season\Output\Get\Mapper $seasonMapper) {
        $this->playerMapper = $playerMapper;
        $this->teamMapper = $teamMapper;
        $this->seasonMapper = $seasonMapper;
    }

    /**
     * @param IPlayerSeasonStatisticEntity $playerStatisticEntity
     * @return Dto|null
     */
    public function map(IPlayerSeasonStatisticEntity $playerStatisticEntity): ?Dto
    {
        $teams = [];
        foreach ($playerStatisticEntity->getTeamEntities()->getAll() as $team) {
            $teams[] = $this->teamMapper->map($team);
        }

        $player = $this->playerMapper->map($playerStatisticEntity->getPlayerEntity());
        $season = $this->seasonMapper->map($playerStatisticEntity->getSeasonEntity());

        $statistics = [];
        foreach ($playerStatisticEntity->getStatisticItems() as $playerStatisticItem) {
            $statistics[] = new StatisticItemDto($playerStatisticItem->getName(), $playerStatisticItem->getValue());
        }

        return new Dto(
            $teams,
            $statistics,
            $season,
            $player
        );
    }
}