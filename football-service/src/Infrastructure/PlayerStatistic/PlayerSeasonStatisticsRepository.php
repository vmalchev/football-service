<?php


namespace Sportal\FootballApi\Infrastructure\PlayerStatistic;


use Doctrine\DBAL\Connection;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticEntity;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticEntityFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerSeasonStatisticRepository;
use Sportal\FootballApi\Domain\PlayerStatistic\IPlayerStatisticItemFactory;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerSeasonStatisticFilter;
use Sportal\FootballApi\Domain\PlayerStatistic\PlayerStatisticType;
use Sportal\FootballApi\Entity\Stage;
use Sportal\FootballApi\Infrastructure\Match\MatchTableMapper;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;

class PlayerSeasonStatisticsRepository implements IPlayerSeasonStatisticRepository
{
    private Connection $connection;

    private IPlayerSeasonStatisticEntityFactory $playerSeasonStatisticEntityFactory;

    private IPlayerStatisticItemFactory $playerStatisticItemFactory;

    private PlayerSeasonStatisticExpressionBuilder $expressionBuilder;

    /**
     * @param Connection $connection
     * @param IPlayerSeasonStatisticEntityFactory $playerSeasonStatisticEntityFactory
     * @param IPlayerStatisticItemFactory $playerStatisticItemFactory
     * @param PlayerSeasonStatisticExpressionBuilder $expressionBuilder
     */
    public function __construct(Connection $connection,
                                IPlayerSeasonStatisticEntityFactory $playerSeasonStatisticEntityFactory,
                                IPlayerStatisticItemFactory $playerStatisticItemFactory,
                                PlayerSeasonStatisticExpressionBuilder $expressionBuilder)
    {
        $this->connection = $connection;
        $this->playerSeasonStatisticEntityFactory = $playerSeasonStatisticEntityFactory;
        $this->playerStatisticItemFactory = $playerStatisticItemFactory;
        $this->expressionBuilder = $expressionBuilder;
    }

    /**
     * @param PlayerSeasonStatisticFilter $filter
     * @return IPlayerSeasonStatisticEntity[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getByFilter(PlayerSeasonStatisticFilter $filter): array
    {
        $builder = $this->connection->createQueryBuilder();
        $nestedBuilder = $this->connection->createQueryBuilder();
        $select = [
            PlayerStatisticTable::FIELD_PLAYER_ID,
            StageTableMapper::FIELD_SEASON_ID,
            'array_to_json(min(teams)) as teams',
            'array_to_json(array_agg(stat_item)) as statistics'
        ];

        $nestedSelect = [
            'tss.tournament_season_id as ' . StageTableMapper::FIELD_SEASON_ID,
            PlayerStatisticTable::FIELD_PLAYER_ID,
            "array_agg(distinct(mps.team_id)) as teams",
            "json_build_object('name', stats->>'name', 'value', SUM((stats->>'value')::integer)) as stat_item"
        ];

        $builder->select($select);

        $nestedQuery = $nestedBuilder
            ->select($nestedSelect)
            ->from(PlayerStatisticTable::TABLE_NAME, 'mps')
            ->innerJoin('mps', MatchTableMapper::TABLE_NAME, 'e', 'e.id = mps.match_id')
            ->innerJoin('e', Stage::TABLE_NAME, 'tss', 'tss.id = e.tournament_season_stage_id')
            ->add('join', ['mps' => ['joinType' => 'cross', 'joinTable' =>
                'lateral jsonb_array_elements(mps.statistics)', 'joinAlias' => 'stats',
                'joinCondition' => null]],
                true)
            ->where($this->expressionBuilder->setFilter($filter)->setQueryBuilder($nestedBuilder)->build())
            ->groupBy([PlayerStatisticTable::FIELD_PLAYER_ID, StageTableMapper::FIELD_SEASON_ID, "stats->>'name'"]);

        $builder->from("({$nestedQuery->getSQL()}) as stats_temp")
            ->groupBy([PlayerStatisticTable::FIELD_PLAYER_ID, StageTableMapper::FIELD_SEASON_ID]);

        $results = $this->connection->executeQuery($builder, $nestedBuilder->getParameters(), $nestedBuilder->getParameterTypes())
            ->fetchAll(\PDO::FETCH_ASSOC);

        $entities = [];
        foreach ($results as $result) {
            $statisticData = json_decode($result[PlayerStatisticTable::FIELD_PLAYER_STATISTIC_ITEM]);
            $statistics = array_map(function ($statisticItem) {
                return $this->playerStatisticItemFactory
                    ->setName(new PlayerStatisticType($statisticItem->name))
                    ->setValue($statisticItem->value)
                    ->create();
            }, $statisticData);

            $entities[] = $this->playerSeasonStatisticEntityFactory
                ->setPlayerId($result[PlayerStatisticTable::FIELD_PLAYER_ID])
                ->setSeasonId($result[StageTableMapper::FIELD_SEASON_ID])
                ->setTeamIds(json_decode($result['teams']))
                ->setStatistics($statistics)
                ->create();
        }

        return $entities;
    }
}