<?php


namespace Sportal\FootballApi\Infrastructure\Group;


use Sportal\FootballApi\Domain\Group\IGroupEntity;
use Sportal\FootballApi\Domain\Group\IGroupEntityFactory;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;

class GroupTableMapper implements TableMapper
{
    const TABLE_NAME = 'stage_group';
    const FIELD_ID = 'id';
    const FIELD_NAME = 'name';
    const FIELD_STAGE_ID = 'tournament_season_stage_id';
    const FIELD_SORT_ORDER = 'order_in_stage';
    const FIELD_UPDATED_AT = 'updated_at';

    private IGroupEntityFactory $entityFactory;

    /**
     * GroupTableMapper constructor.
     * @param IGroupEntityFactory $entityFactory
     */
    public function __construct(IGroupEntityFactory $entityFactory)
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
            self::FIELD_STAGE_ID,
            self::FIELD_NAME,
            self::FIELD_SORT_ORDER
        ];
    }

    public function toEntity(array $data): IGroupEntity
    {
        return $this->entityFactory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setName($data[self::FIELD_NAME])
            ->setStageId($data[self::FIELD_STAGE_ID])
            ->setSortorder($data[self::FIELD_SORT_ORDER] ?? null)
            ->create();
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return null;
    }
}