<?php


namespace Sportal\FootballApi\Infrastructure\Country;


use Sportal\FootballApi\Domain\Country\ICountryEntity;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Entity\CountryEntity;

class CountryTableMapper implements TableMapper
{
    const TABLE_NAME = 'country';

    const ID_FIELD = 'id';

    const NAME_FIELD = 'name';

    const CODE_FIELD = 'code';

    private JoinFactory $joinFactory;

    /**
     * CountryTableMapper constructor.
     * @param JoinFactory $joinFactory
     */
    public function __construct(JoinFactory $joinFactory)
    {
        $this->joinFactory = $joinFactory;
    }

    public function toEntity(array $data): ICountryEntity
    {
        return new CountryEntity(
            $data[static::ID_FIELD],
            $data[static::NAME_FIELD],
            $data[static::CODE_FIELD] ?? null
        );
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getInnerJoin()
    {
        return $this->joinFactory->createInner(self::TABLE_NAME, $this->getColumns())
            ->setFactory([$this, 'toEntity']);
    }

    public function getColumns(): array
    {
        return [
            static::ID_FIELD,
            static::NAME_FIELD,
            static::CODE_FIELD,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return null;
    }
}