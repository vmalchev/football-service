<?php


namespace Sportal\FootballApi\Infrastructure\MatchStatus;


use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntityFactory;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;

class MatchStatusTableMapper implements TableMapper
{
    const TABLE_NAME = 'event_status';

    const FIELD_ID = 'id';
    const FIELD_NAME = 'name';
    const FIELD_SHORT_NAME = 'short_name';
    const FIELD_CODE = 'code';
    const FIELD_TYPE = 'type';

    private IMatchStatusEntityFactory $entityFactory;

    /**
     * MatchStatusTableMapper constructor.
     * @param IMatchStatusEntityFactory $entityFactory
     */
    public function __construct(IMatchStatusEntityFactory $entityFactory)
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
            self::FIELD_NAME,
            self::FIELD_SHORT_NAME,
            self::FIELD_CODE,
            self::FIELD_TYPE
        ];
    }

    public function toEntity(array $data): IMatchStatusEntity
    {
        return $this->entityFactory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setName($data[self::FIELD_NAME])
            ->setShortName($data[self::FIELD_SHORT_NAME])
            ->setType(new MatchStatusType($data[self::FIELD_TYPE]))
            ->setCode($data[self::FIELD_CODE])
            ->create();
    }

    public function getRelations(): ?array
    {
        return null;
    }
}