<?php


namespace Sportal\FootballApi\Infrastructure\Venue;


use Sportal\FootballApi\Domain\Venue\IVenueEntityFactory;
use Sportal\FootballApi\Infrastructure\City\CityTableMapper;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;

class VenueTableMapper implements TableMapper
{

    const TABLE_NAME = "venue";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_COUNTRY = "country";
    const FIELD_CITY_ID = "city_id";
    const FIELD_PROFILE = "profile";

    private IVenueEntityFactory $factory;
    private RelationFactory $relationFactory;

    /**
     * VenueTableMapper constructor.
     * @param IVenueEntityFactory $factory
     * @param RelationFactory $relationFactory
     */
    public function __construct(IVenueEntityFactory $factory, RelationFactory $relationFactory)
    {
        $this->factory = $factory;
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
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
            self::FIELD_CITY_ID,
            self::FIELD_PROFILE
        ];
    }

    public function toEntity(array $data): object
    {
        return $this->factory->createFromArray($data);
    }

    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(CountryTableMapper::TABLE_NAME, RelationType::REQUIRED())->create(),
            $this->relationFactory->from(CityTableMapper::TABLE_NAME, RelationType::OPTIONAL())->create()
        ];
    }
}