<?php


namespace Sportal\FootballApi\Infrastructure\President;


use Sportal\FootballApi\Domain\President\IPresidentEntityFactory;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;

class PresidentTableMapper implements TableMapper
{
    const TABLE_NAME = "president";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";

    private IPresidentEntityFactory $factory;

    /**
     * PresidentTableMapper constructor.
     * @param IPresidentEntityFactory $factory
     */
    public function __construct(IPresidentEntityFactory $factory)
    {
        $this->factory = $factory;
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
        ];
    }

    public function toEntity(array $data): object
    {
        return $this->factory->createFromArray($data);
    }

    public function getRelations(): ?array
    {
        return null;
    }
}