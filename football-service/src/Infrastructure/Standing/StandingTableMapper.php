<?php


namespace Sportal\FootballApi\Infrastructure\Standing;


use Sportal\FootballApi\Domain\Standing\StandingEntityName;
use Sportal\FootballApi\Domain\Standing\StandingType;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;

class StandingTableMapper implements TableMapper
{
    const TABLE_NAME = 'standing';
    const FIELD_ID = 'id';
    const FIELD_TYPE = 'type';
    const FIELD_ENTITY = 'entity';
    const FIELD_ENTITY_ID = 'entity_id';

    private RelationFactory $relationFactory;

    /**
     * StandingTableMapper constructor.
     * @param RelationFactory $relationFactory
     */
    public function __construct(RelationFactory $relationFactory)
    {
        $this->relationFactory = $relationFactory;
    }


    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_TYPE,
            self::FIELD_ENTITY,
            self::FIELD_ENTITY_ID
        ];
    }

    public function toEntity(array $data): object
    {
        return new StandingEntity($data[self::FIELD_ID],
            new StandingType($data[self::FIELD_TYPE]),
            new StandingEntityName($data[self::FIELD_ENTITY]),
            $data[self::FIELD_ENTITY_ID]);
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return null;
    }
}