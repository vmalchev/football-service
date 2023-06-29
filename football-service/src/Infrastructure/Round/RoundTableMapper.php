<?php

namespace Sportal\FootballApi\Infrastructure\Round;

use Sportal\FootballApi\Domain\Round\IRoundEntity;
use Sportal\FootballApi\Domain\Round\IRoundEntityFactory;
use Sportal\FootballApi\Domain\Round\RoundType;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateTimeConverter;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;

class RoundTableMapper implements TableMapper
{

    const TABLE_NAME = 'round_type';

    const FIELD_ID = 'id';
    const FIELD_KEY = 'key';
    const FIELD_NAME = 'name';
    const FIELD_TYPE = 'type';
    const START_TIME_ALIAS = "start_time";
    const END_TIME_ALIAS = "end_time";

    private IRoundEntityFactory $entityFactory;

    public function __construct(IRoundEntityFactory $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getColumns(): array
    {
        return [
          self::FIELD_ID,
          self::FIELD_KEY,
          self::FIELD_NAME,
          self::FIELD_TYPE
        ];
    }

    public function toEntity(array $data): IRoundEntity
    {
        return $this->entityFactory
            ->setId($data[self::FIELD_ID])
            ->setKey($data[self::FIELD_KEY])
            ->setName($data[self::FIELD_NAME])
            ->setType($this->createRoundType($data[self::FIELD_TYPE]))
            ->setStartDate(array_key_exists(self::START_TIME_ALIAS, $data) ? DateTimeConverter::fromValue($data[self::START_TIME_ALIAS]) : null)
            ->setEndDate(array_key_exists(self::END_TIME_ALIAS, $data) ? DateTimeConverter::fromValue($data[self::END_TIME_ALIAS]) : null)
            ->create();
    }

    public function getRelations(): ?array
    {
        return null;
    }

    private function createRoundType(string $fieldType): ?RoundType
    {
        if (in_array($fieldType, RoundType::values())) {
            return new RoundType($fieldType);
        } else {
            return null;
        }
    }
}