<?php


namespace Sportal\FootballApi\Infrastructure\Tournament;

use Sportal\FootballApi\Domain\Tournament\TournamentGender;
use Sportal\FootballApi\Domain\Tournament\TournamentRegion;
use Sportal\FootballApi\Domain\Tournament\TournamentType;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;


class TournamentTableMapper implements TableMapper
{
    const TABLE_NAME = "tournament";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_COUNTRY = "country";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_REGIONAL_LEAGUE = "regional_league";
    const FIELD_GENDER = "gender";
    const FIELD_TYPE = "type";
    const FIELD_REGION = "region";

    private JoinFactory $joinFactory;

    private CountryTableMapper $countryMapper;

    private RelationFactory $relationFactory;

    /**
     * @param JoinFactory $joinFactory
     * @param CountryTableMapper $countryMapper
     * @param RelationFactory $relationFactory
     */
    public function __construct(JoinFactory $joinFactory, CountryTableMapper $countryMapper, RelationFactory $relationFactory)
    {
        $this->joinFactory = $joinFactory;
        $this->countryMapper = $countryMapper;
        $this->relationFactory = $relationFactory;
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_COUNTRY_ID,
            self::FIELD_REGIONAL_LEAGUE,
            self::FIELD_GENDER,
            self::FIELD_TYPE,
            self::FIELD_REGION
        ];
    }

    public function toEntity(array $data): TournamentEntity
    {

        return new TournamentEntity(
            $data[TournamentTableMapper::FIELD_ID],
            $data[TournamentTableMapper::FIELD_NAME],
            $data[TournamentTableMapper::FIELD_COUNTRY],
            $data[TournamentTableMapper::FIELD_COUNTRY_ID],
            $data[TournamentTableMapper::FIELD_REGIONAL_LEAGUE],
            $data[TournamentTableMapper::FIELD_GENDER] != null ? new TournamentGender($data[TournamentTableMapper::FIELD_GENDER]) : null,
            $data[TournamentTableMapper::FIELD_REGION] != null ? new TournamentRegion($data[TournamentTableMapper::FIELD_REGION]) : null,
            $data[TournamentTableMapper::FIELD_TYPE] != null ? new TournamentType($data[TournamentTableMapper::FIELD_TYPE]) : null
        );
    }

    public function getInnerJoin()
    {
        return $this->joinFactory->createInner(self::TABLE_NAME, $this->getColumns())
            ->setFactory([$this, 'toEntity'])
            ->addChild($this->countryMapper->getInnerJoin());
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

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }
}