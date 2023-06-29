<?php

namespace Sportal\FootballApi\Infrastructure\Round;

use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Round\IRoundRepository;
use Sportal\FootballApi\Domain\Round\RoundFilter;
use Sportal\FootballApi\Domain\Round\RoundType;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\DatabaseUpdate;
use Sportal\FootballApi\Infrastructure\Match\MatchTableMapper;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;

class RoundRepository implements IRoundRepository
{

    private Database $database;

    private DatabaseUpdate $databaseUpdate;

    private RoundTableMapper $roundTableMapper;

    public function __construct(Database $database,
                                DatabaseUpdate $databaseUpdate,
                                RoundTableMapper $roundTableMapper)
    {
        $this->database = $database;
        $this->databaseUpdate = $databaseUpdate;
        $this->roundTableMapper = $roundTableMapper;
    }

    public function findByKeyAndType(string $key, RoundType $type): ?IRoundEntity
    {
        $expr = $this->database->andExpression()
            ->eq(RoundTableMapper::FIELD_KEY, $key)
            ->eq(RoundTableMapper::FIELD_TYPE, $type->getValue());
        $query = $this->database->createQuery(RoundTableMapper::TABLE_NAME)
            ->where($expr);
        return $this->database->getSingleResult($query, [$this->roundTableMapper, 'toEntity']);
    }

    public function findAll(RoundFilter $filter): array
    {
        $joinFactory = $this->database->getJoinFactory();
        $matchJoin = $joinFactory
            ->createLeft(MatchTableMapper::TABLE_NAME, [])
            ->setForeignKey(RoundTableMapper::FIELD_ID)
            ->setReference(MatchTableMapper::FIELD_ROUND_TYPE_ID)
            ->addChild($joinFactory->createLeft(StageTableMapper::TABLE_NAME, []));

        $query = $this->database->createQuery(RoundTableMapper::TABLE_NAME)
            ->addSelect("MIN(" . MatchTableMapper::FIELD_KICKOFF_TIME . ")", RoundTableMapper::START_TIME_ALIAS)
            ->addSelect("MAX("  . MatchTableMapper::FIELD_KICKOFF_TIME . ")", RoundTableMapper::END_TIME_ALIAS)
            ->addJoin($matchJoin)
            ->addGroupBy(StageTableMapper::TABLE_NAME, StageTableMapper::FIELD_ID)
            ->addGroupBy(RoundTableMapper::TABLE_NAME, RoundTableMapper::FIELD_ID)
            ->addOrderByAlias(RoundTableMapper::START_TIME_ALIAS);

        if (!is_null($filter->getSeasonId())) {
            $query->where($this->database->andExpression()
                ->eq(StageTableMapper::FIELD_SEASON_ID, $filter->getSeasonId(), StageTableMapper::TABLE_NAME));
        } elseif (!is_null($filter->getStageId())) {
            $query->where($this->database->andExpression()
                ->eq(MatchTableMapper::FIELD_STAGE_ID, $filter->getStageId(), MatchTableMapper::TABLE_NAME));
        }

        return $this->database->getQueryResults($query, [$this->roundTableMapper, 'toEntity']);
    }

    public function insert(IRoundEntity $roundEntity): IRoundEntity
    {
        return $this->databaseUpdate->insertGeneratedId(RoundTableMapper::TABLE_NAME, $roundEntity);
    }

    public function exists(string $id): bool
    {
        return $this->database->exists($this->roundTableMapper->getTableName(), [RoundTableMapper::FIELD_ID => $id]);
    }

}