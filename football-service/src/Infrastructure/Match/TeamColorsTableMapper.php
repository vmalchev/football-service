<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Closure;
use Sportal\FootballApi\Domain\Match\ColorEntityType;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntity;
use Sportal\FootballApi\Domain\Team\ITeamColorsEntityFactory;
use Sportal\FootballApi\Infrastructure\Database\Database;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFilter;
use Sportal\FootballApi\Infrastructure\Database\Query\TableColumn;
use Sportal\FootballApi\Infrastructure\Database\Relation\Relation;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Team\TeamColorsEntityFactory;
use Sportal\FootballApi\Infrastructure\Team\TeamColorsTable;

class TeamColorsTableMapper
{

    private Database $db;
    private RelationFactory $relationFactory;
    private ITeamColorsEntityFactory $teamColorsEntityFactory;

    /**
     * TeamColorsTableMapper constructor.
     * @param Database $db
     * @param RelationFactory $relationFactory
     * @param ITeamColorsEntityFactory $teamColorsEntityFactory
     */
    public function __construct(Database $db,
                                RelationFactory $relationFactory,
                                ITeamColorsEntityFactory $teamColorsEntityFactory)
    {
        $this->db = $db;
        $this->relationFactory = $relationFactory;
        $this->teamColorsEntityFactory = $teamColorsEntityFactory;
    }

    public function getMatchColorsRelation(): Relation
    {
        return $this->relationFactory->from(TeamColorsTable::TABLE_NAME, RelationType::OPTIONAL())
            ->setColumns(TeamColorsTable::getColumns())
            ->setJoinCondition($this->db->andExpression()->eq(TeamColorsTable::FIELD_ENTITY_TYPE, ColorEntityType::MATCH(), 'match_colors')
                ->add(new JoinFilter(new TableColumn(MatchTableMapper::TABLE_NAME, MatchTableMapper::FIELD_ID),
                    new TableColumn('match_colors', TeamColorsTable::FIELD_ENTITY_ID))))
            ->setAliasName('match_colors')
            ->setObjectFactory(Closure::fromCallable([$this, "toEntity"]))
            ->create();
    }

    public function getHomeTeamColorsRelation(): Relation
    {
        return $this->relationFactory->from(TeamColorsTable::TABLE_NAME, RelationType::OPTIONAL())
            ->setColumns(TeamColorsTable::getColumns())
            ->setJoinCondition($this->db->andExpression()->eq(TeamColorsTable::FIELD_ENTITY_TYPE, ColorEntityType::TEAM(), 'home_team_colors')
                ->add(new JoinFilter(new TableColumn(MatchTableMapper::TABLE_NAME, MatchTableMapper::FIELD_HOME_TEAM_ID),
                    new TableColumn('home_team_colors', TeamColorsTable::FIELD_ENTITY_ID))))
            ->setAliasName('home_team_colors')
            ->setObjectFactory(Closure::fromCallable([$this, "toEntity"]))
            ->create();
    }

    public function getAwayTeamColorsRelation(): Relation
    {
        return $this->relationFactory->from(TeamColorsTable::TABLE_NAME, RelationType::OPTIONAL())
            ->setColumns(TeamColorsTable::getColumns())
            ->setJoinCondition($this->db->andExpression()->eq(TeamColorsTable::FIELD_ENTITY_TYPE, ColorEntityType::TEAM(), 'away_team_colors')
                ->add(new JoinFilter(new TableColumn(MatchTableMapper::TABLE_NAME, MatchTableMapper::FIELD_AWAY_TEAM_ID),
                    new TableColumn('away_team_colors', TeamColorsTable::FIELD_ENTITY_ID))))
            ->setAliasName('away_team_colors')
            ->setObjectFactory(Closure::fromCallable([$this, "toEntity"]))
            ->create();
    }

    public function toEntity(array $data): ITeamColorsEntity
    {
        $factory = $this->teamColorsEntityFactory->setEmpty();
        $factory->setEntityId($data[TeamColorsTable::FIELD_ENTITY_ID]);
        $factory->setEntityType($data[TeamColorsTable::FIELD_ENTITY_TYPE]);
        $factory->setColors(json_decode($data[TeamColorsTable::FIELD_COLORS], true));

        return $factory->create();
    }
}