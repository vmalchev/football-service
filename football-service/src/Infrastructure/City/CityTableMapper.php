<?php


namespace Sportal\FootballApi\Infrastructure\City;


use Sportal\FootballApi\Domain\City\ICityEntity;
use Sportal\FootballApi\Domain\City\ICityEntityFactory;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;

class CityTableMapper implements TableMapper
{

    const TABLE_NAME = "city";

    const FIELD_ID = "id";

    const FIELD_NAME = "name";

    const FIELD_COUNTRY_ID = "country_id";

    const FIELD_COUNTRY = "country";

    const FIELD_UPDATED_AT = "updated_at";

    const FIELD_CREATED_AT = "created_at";

    private CountryTableMapper $countryMapper;

    private ICityEntityFactory $factory;

    private JoinFactory $joinFactory;

    private RelationFactory $relationFactory;

    /**
     * CityTableMapper constructor.
     * @param ICityEntityFactory $factory
     * @param JoinFactory $joinFactory
     * @param CountryTableMapper $countryMapper
     * @param RelationFactory $relationFactory
     */
    public function __construct(ICityEntityFactory $factory,
                                JoinFactory $joinFactory,
                                CountryTableMapper $countryMapper,
                                RelationFactory $relationFactory)
    {
        $this->factory = $factory;
        $this->joinFactory = $joinFactory;
        $this->countryMapper = $countryMapper;
        $this->relationFactory = $relationFactory;
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function create(?array $data): ?ICityEntity
    {
        return $this->factory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setName($data[self::FIELD_NAME])
            ->setCountryId($data[self::FIELD_COUNTRY_ID])
            ->setCountry($data[CityTable::FIELD_COUNTRY] ?? null)
            ->create();
    }

    public function getInnerJoin(): Join
    {
        return $this->joinFactory
            ->createInner($this->getTableName(), $this->getColumns())
            ->setFactory([$this, 'create'])
            ->addChild($this->countryMapper->getInnerJoin());
    }

    public function getLeftJoin(): Join
    {
        return $this->joinFactory
            ->createLeft($this->getTableName(), $this->getColumns())
            ->setFactory([$this, 'create'])
            ->addChild($this->countryMapper->getInnerJoin());
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
        ];
    }

    public function toEntity(array $data): ICityEntity
    {
        return $this->create($data);
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(CountryTableMapper::TABLE_NAME, RelationType::REQUIRED())->create()
        ];
    }
}