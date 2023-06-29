<?php


namespace Sportal\FootballApi\Infrastructure\Stage;


use Sportal\FootballApi\Domain\Stage\IStageEntity;
use Sportal\FootballApi\Domain\Stage\IStageEntityFactory;
use Sportal\FootballApi\Domain\Stage\StageType;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateConverter;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchCoverageConverter;
use Sportal\FootballApi\Infrastructure\Season\SeasonTableMapper;

class StageTableMapper implements TableMapper
{
    const TABLE_NAME = 'tournament_season_stage';
    const FIELD_ID = 'id';
    const FIELD_NAME = 'name';
    const FIELD_SEASON = 'tournament_season';
    const FIELD_SEASON_ID = 'tournament_season_id';
    const FIELD_CUP = 'cup';
    const FIELD_STAGE_GROUPS = 'stage_groups';
    const FIELD_START_DATE = 'start_date';
    const FIELD_END_DATE = 'end_date';
    const FIELD_CONFEDERATION = 'confederation';
    const FIELD_UPDATED_AT = 'updated_at';
    const FIELD_LIVE = 'live';
    const FIELD_ORDER_IN_SEASON = 'order_in_season';
    const FIELD_TYPE = 'type';
    const FIELD_COUNTRY_ID = 'country_id';

    private IStageEntityFactory $factory;
    private JoinFactory $joinFactory;
    private SeasonTableMapper $seasonMapper;
    private RelationFactory $relationFactory;

    /**
     * StageTableMapper constructor.
     * @param IStageEntityFactory $factory
     * @param JoinFactory $joinFactory
     * @param SeasonTableMapper $seasonMapper
     * @param RelationFactory $relationFactory
     */
    public function __construct(IStageEntityFactory $factory, JoinFactory $joinFactory, SeasonTableMapper $seasonMapper, RelationFactory $relationFactory)
    {
        $this->factory = $factory;
        $this->joinFactory = $joinFactory;
        $this->seasonMapper = $seasonMapper;
        $this->relationFactory = $relationFactory;
    }


    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getInnerJoin(): Join
    {
        return $this->joinFactory
            ->createInner(self::TABLE_NAME, $this->getColumns())
            ->setFactory([$this, 'toEntity'])
            ->addChild($this->seasonMapper->getInnerJoin());
    }

    public function toEntity(array $data): IStageEntity
    {
        return $this->factory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setName($data[self::FIELD_NAME])
            ->setSeasonId($data[self::FIELD_SEASON_ID])
            ->setSeason($data[self::FIELD_SEASON] ?? null)
            ->setType($data[self::FIELD_TYPE] !== null ? StageType::from($data[self::FIELD_TYPE]) : null)
            ->setStartDate(DateConverter::fromValue($data[self::FIELD_START_DATE]))
            ->setEndDate(DateConverter::fromValue($data[self::FIELD_END_DATE]))
            ->setConfederation($data[self::FIELD_CONFEDERATION])
            ->setOrderInSeason($data[self::FIELD_ORDER_IN_SEASON] ?? null)
            ->setCoverage(MatchCoverageConverter::fromValue($data[self::FIELD_LIVE]))
            ->create();
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_SEASON_ID,
            self::FIELD_START_DATE,
            self::FIELD_END_DATE,
            self::FIELD_CONFEDERATION,
            self::FIELD_LIVE,
            self::FIELD_TYPE,
            self::FIELD_ORDER_IN_SEASON
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(SeasonTableMapper::TABLE_NAME, RelationType::REQUIRED())->create()
        ];
    }
}