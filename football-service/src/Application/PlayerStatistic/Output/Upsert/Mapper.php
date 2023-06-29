<?php

namespace Sportal\FootballApi\Application\PlayerStatistic\Output\Upsert;

use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticEntity;

class Mapper
{
    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return Dto|null
     */
    public function map(IPlayerStatisticEntity $playerStatisticEntity): ?Dto
    {
        if (is_null($playerStatisticEntity)) {
            return null;
        }
        return new Dto(
            $playerStatisticEntity->getPlayerId(),
            $playerStatisticEntity->getMatchId(),
            $playerStatisticEntity->getTeamId(),
            $playerStatisticEntity->getStatisticItems(),
            $playerStatisticEntity->getOrigin()->getKey()
        );
    }
}