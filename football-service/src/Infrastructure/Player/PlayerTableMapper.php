<?php


namespace Sportal\FootballApi\Infrastructure\Player;


use Sportal\FootballApi\Domain\Player\IPlayerEntity;
use Sportal\FootballApi\Infrastructure\City\CityTableMapper;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;

class PlayerTableMapper implements TableMapper
{
    const TABLE_NAME = "player";
    const FIELD_ID = "id";

    private JoinFactory $joinFactory;

    private CityTableMapper $cityMapper;

    private CountryTableMapper $countryMapper;

    private RelationFactory $relationFactory;

    /**
     * PlayerTableMapper constructor.
     * @param JoinFactory $joinFactory
     * @param CityTableMapper $cityMapper
     * @param CountryTableMapper $countryMapper
     * @param RelationFactory $relationFactory
     */
    public function __construct(JoinFactory $joinFactory, CityTableMapper $cityMapper, CountryTableMapper $countryMapper, RelationFactory $relationFactory)
    {
        $this->joinFactory = $joinFactory;
        $this->cityMapper = $cityMapper;
        $this->countryMapper = $countryMapper;
        $this->relationFactory = $relationFactory;
    }

    public function create(array $data)
    {
        return PlayerEntity::create($data);
    }

    public function getInnerJoin()
    {
        return $this->joinFactory->createInner(PlayerTable::TABLE_NAME, PlayerTable::getColumns())
            ->setFactory([$this, 'create'])
            ->addChild($this->cityMapper->getLeftJoin())
            ->addChild($this->countryMapper->getInnerJoin());
    }

    public function getLeftJoin(): Join
    {
        return $this->joinFactory->createLeft(PlayerTable::TABLE_NAME, PlayerTable::getColumns())
            ->setFactory([$this, 'create'])
            ->addChild($this->cityMapper->getLeftJoin())
            ->addChild($this->countryMapper->getInnerJoin());
    }


    public function getCountryJoin()
    {
        return $this->countryMapper->getInnerJoin();
    }

    public function getCityJoin()
    {
        return $this->cityMapper->getLeftJoin();
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return PlayerTable::getColumns();
    }

    public function toEntity(array $data): IPlayerEntity
    {
        return $this->create($data);
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(CountryTableMapper::TABLE_NAME, RelationType::REQUIRED())->create(),
            $this->relationFactory->from(CityTableMapper::TABLE_NAME, RelationType::OPTIONAL())->create()
        ];
    }
}