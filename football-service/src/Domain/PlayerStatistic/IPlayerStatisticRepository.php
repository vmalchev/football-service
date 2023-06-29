<?php


namespace Sportal\FootballApi\Domain\PlayerStatistic;


interface IPlayerStatisticRepository
{
    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticEntity
     */
    public function insert(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticEntity;

    /**
     * @param IPlayerStatisticEntity $playerStatisticEntity
     * @return IPlayerStatisticEntity
     */
    public function update(IPlayerStatisticEntity $playerStatisticEntity): IPlayerStatisticEntity;

    /**
     * @param string $matchId
     * @param IPlayerStatisticEntity[] $playerStatisticEntities
     */
    public function upsertByMatchId(string $matchId, array $playerStatisticEntities);
}