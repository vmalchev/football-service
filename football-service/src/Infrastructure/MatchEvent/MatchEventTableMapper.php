<?php


namespace Sportal\FootballApi\Infrastructure\MatchEvent;

use Sportal\FootballApi\Domain\MatchEvent\IMatchEventEntity;
use Sportal\FootballApi\Domain\MatchEvent\MatchEventType;
use Sportal\FootballApi\Domain\MatchEvent\TeamPositionStatus;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Match\MatchTableMapper;
use Sportal\FootballApi\Infrastructure\Player\PlayerTableMapper;


class MatchEventTableMapper implements TableMapper
{
    const TABLE_NAME = "event_incident";

    const FIELD_ID = "id";
    const FIELD_EVENT_ID = "event_id";
    const FIELD_TYPE = "type";
    const FIELD_HOME_TEAM = "home_team";

    const FIELD_MINUTE = "minute";
    const FIELD_PLAYER = "player";
    const FIELD_PLAYER_ID = "player_id";
    const FIELD_PLAYER_NAME = "player_name";
    const FIELD_REL_PLAYER = "rel_player";
    const FIELD_REL_PLAYER_ID = "rel_player_id";
    const FIELD_REL_PLAYER_NAME = "rel_player_name";
    const FIELD_GOAL_HOME = "goal_home";
    const FIELD_GOAL_AWAY = "goal_away";
    const FIELD_SORT_ORDER = "sortorder";
    const FIELD_MATCH = 'match';
    const FIELD_UPDATED_AT = "updated_at";

    const FIELD_DELETED = "deleted";


    private MatchEventEntityFactory $factory;

    private RelationFactory $relationFactory;

    /**
     * MatchEventTableMapper constructor.
     * @param MatchEventEntityFactory $factory
     * @param RelationFactory $relationFactory
     */
    public function __construct(MatchEventEntityFactory $factory, RelationFactory $relationFactory)
    {
        $this->factory = $factory;
        $this->relationFactory = $relationFactory;
    }


    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_EVENT_ID,
            self::FIELD_TYPE,
            self::FIELD_HOME_TEAM,
            self::FIELD_MINUTE,
            self::FIELD_PLAYER_ID,
            self::FIELD_PLAYER_NAME,
            self::FIELD_REL_PLAYER,
            self::FIELD_REL_PLAYER_ID,
            self::FIELD_REL_PLAYER_NAME,
            self::FIELD_GOAL_HOME,
            self::FIELD_GOAL_AWAY,
            self::FIELD_SORT_ORDER,
            self::FIELD_UPDATED_AT,
            self::FIELD_DELETED
        ];
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }


    public function toEntity(array $data): IMatchEventEntity
    {
        $teamPosition = TeamPositionStatusConverter::fromValue($data[self::FIELD_HOME_TEAM]);
        $teamId = TeamPositionStatus::HOME()->equals($teamPosition) ?
            $data[self::FIELD_MATCH][MatchTableMapper::FIELD_HOME_TEAM_ID] : $data[self::FIELD_MATCH][MatchTableMapper::FIELD_AWAY_TEAM_ID];

        return $this->factory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setMatchId($data[self::FIELD_EVENT_ID])
            ->setEventType(new MatchEventType($data[self::FIELD_TYPE]))
            ->setTeamPosition($teamPosition)
            ->setTeamId($teamId)
            ->setMinute($data[self::FIELD_MINUTE])
            ->setPrimaryPlayer($data[self::FIELD_PLAYER] ?? null)
            ->setPrimaryPlayerId($data[self::FIELD_PLAYER_ID])
            ->setSecondaryPlayer($data[self::FIELD_REL_PLAYER] ?? null)
            ->setSecondaryPlayerId($data[self::FIELD_REL_PLAYER_ID])
            ->setGoalHome($data[self::FIELD_GOAL_HOME])
            ->setGoalAway($data[self::FIELD_GOAL_AWAY])
            ->setSortOrder($data[self::FIELD_SORT_ORDER])
            ->create();
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(PlayerTableMapper::TABLE_NAME, RelationType::OPTIONAL())->create(),
            $this->relationFactory->from(PlayerTableMapper::TABLE_NAME, RelationType::OPTIONAL())
                ->setForeignKey(self::FIELD_REL_PLAYER_ID)
                ->setObjectKey(self::FIELD_REL_PLAYER)
                ->create(),
            $this->relationFactory->from(MatchTableMapper::TABLE_NAME, RelationType::REQUIRED())
                ->setColumns([MatchTableMapper::FIELD_HOME_TEAM_ID, MatchTableMapper::FIELD_AWAY_TEAM_ID])
                ->setObjectKey(self::FIELD_MATCH)
                ->create()
        ];
    }
}