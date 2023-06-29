<?php

namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerSeasonStatisticFilter;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;

class PlayerSeasonStatisticExpressionBuilder
{
    private PlayerSeasonStatisticFilter $filter;

    private QueryBuilder $queryBuilder;

    /**
     * @param PlayerSeasonStatisticFilter $filter
     * @return PlayerSeasonStatisticExpressionBuilder
     */
    public function setFilter(PlayerSeasonStatisticFilter $filter): PlayerSeasonStatisticExpressionBuilder
    {
        $builder = clone $this;
        $builder->filter = $filter;
        return $builder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return PlayerSeasonStatisticExpressionBuilder
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder): PlayerSeasonStatisticExpressionBuilder
    {
        $builder = clone $this;
        $builder->queryBuilder = $queryBuilder;
        return $builder;
    }

    public function build(): CompositeExpression
    {
        $buildMap = [
            $this->getPlayerIds(),
            $this->getSeasonIds(),
            $this->getTeamId()
        ];

        $queryExpression = $this->queryBuilder->expr()->andX();

        foreach ($buildMap as $expression) {
            if ($expression !== null) {
                $queryExpression->add($expression);
            }
        }

        return $queryExpression;
    }

    private function getPlayerIds(): ?string
    {
        if ($this->filter->getPlayerIds()) {
            $positionalParams = [];
            foreach ($this->filter->getPlayerIds() as $playerId) {
                $positionalParams[] = $this->queryBuilder->createPositionalParameter($playerId);
            }
            return $this->queryBuilder->expr()->in(PlayerStatisticTable::FIELD_PLAYER_ID, $positionalParams);
        }
        return null;
    }

    private function getSeasonIds(): ?string
    {
        if ($this->filter->getSeasonIds()) {
            $positionalParams = [];
            foreach ($this->filter->getSeasonIds() as $seasonId) {
                $positionalParams[] = $this->queryBuilder->createPositionalParameter($seasonId);
            }
            return $this->queryBuilder->expr()->in(StageTableMapper::FIELD_SEASON_ID, $positionalParams);
        }
        return null;
    }

    private function getTeamId(): ?string
    {
        if ($this->filter->getTeamId()) {
            return $this->queryBuilder->expr()->eq(PlayerStatisticTable::FIELD_TEAM_ID,
                $this->queryBuilder->createPositionalParameter($this->filter->getTeamId()));
        }
        return null;
    }

}