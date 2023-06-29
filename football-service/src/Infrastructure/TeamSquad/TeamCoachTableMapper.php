<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use DateTimeImmutable;
use Sportal\FootballApi\Infrastructure\Coach\CoachTable;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;

class TeamCoachTableMapper implements TableMapper
{
    const TABLE_NAME = "team_coach";
    const FIELD_ID = "id";
    const FIELD_TEAM_ID = "team_id";
    const FIELD_COACH_ID = "coach_id";
    const FIELD_COACH = "coach";
    const FIELD_START_DATE = "start_date";
    const FIELD_END_DATE = "end_date";
    const FIELD_ACTIVE = "active";

    private TeamCoachEntityFactory $entityFactory;
    private RelationFactory $relationFactory;

    /**
     * TeamCoachTableMapper constructor.
     * @param TeamCoachEntityFactory $entityFactory
     * @param RelationFactory $relationFactory
     */
    public function __construct(TeamCoachEntityFactory $entityFactory, RelationFactory $relationFactory)
    {
        $this->entityFactory = $entityFactory;
        $this->relationFactory = $relationFactory;
    }


    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_TEAM_ID,
            self::FIELD_COACH_ID,
            self::FIELD_START_DATE,
            self::FIELD_END_DATE,
            self::FIELD_ACTIVE,
        ];
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function toEntity(array $data): object
    {
        return $this->entityFactory->setEmpty()
            ->setTeamId($data[TeamCoachTableMapper::FIELD_TEAM_ID])
            ->setCoachId($data[TeamCoachTableMapper::FIELD_COACH_ID])
            ->setCoach($data[TeamCoachTableMapper::FIELD_COACH] ?? null)
            ->setStatus(StatusDatabaseConverter::fromValue($data[TeamCoachTableMapper::FIELD_ACTIVE]))
            ->setStartDate(!is_null($data[TeamCoachTableMapper::FIELD_START_DATE]) ? new DateTimeImmutable($data[TeamCoachTableMapper::FIELD_START_DATE]) : null)
            ->setEndDate(!is_null($data[TeamCoachTableMapper::FIELD_END_DATE]) ? new DateTimeImmutable($data[TeamCoachTableMapper::FIELD_END_DATE]) : null)
            ->create()
            ->withId($data[self::FIELD_ID]);
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [$this->relationFactory->from(CoachTable::TABLE_NAME, RelationType::REQUIRED())->create()];
    }
}