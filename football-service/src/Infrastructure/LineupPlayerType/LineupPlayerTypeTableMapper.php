<?php


namespace Sportal\FootballApi\Infrastructure\LineupPlayerType;


use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeEntity;
use Sportal\FootballApi\Domain\LineupPlayerType\ILineupPlayerTypeFactory;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;

class LineupPlayerTypeTableMapper
{
    const TABLE_NAME = "event_player_type";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_CATEGORY = "category";
    const FIELD_CODE = "code";
    const FIELD_SORT_ORDER = "sortorder";

    private ILineupPlayerTypeFactory $factory;
    private JoinFactory $joinFactory;

    /**
     * LineupPlayerTypeTableMapper constructor.
     * @param ILineupPlayerTypeFactory $factory
     * @param JoinFactory $joinFactory
     */
    public function __construct(ILineupPlayerTypeFactory $factory, JoinFactory $joinFactory)
    {
        $this->factory = $factory;
        $this->joinFactory = $joinFactory;
    }

    public function create(array $data): ILineupPlayerTypeEntity
    {
        $sortOrder = $data[self::FIELD_SORT_ORDER] ?? PHP_INT_MAX;
        return $this->factory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setName($data[self::FIELD_NAME])
            ->setCategory($data[self::FIELD_CATEGORY])
            ->setCode($data[self::FIELD_CODE])
            ->setSortOrder($sortOrder)
            ->create();
    }

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function getInnerJoin(): Join
    {
        return $this->joinFactory->createInner(self::TABLE_NAME, $this->getColumns())
            ->setFactory([$this, 'create']);
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_CATEGORY,
            self::FIELD_CODE,
            self::FIELD_SORT_ORDER
        ];
    }
}