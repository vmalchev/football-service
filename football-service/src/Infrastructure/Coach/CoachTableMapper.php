<?php


namespace Sportal\FootballApi\Infrastructure\Coach;


use DateTimeImmutable;
use Sportal\FootballApi\Domain\Coach\ICoachEntity;
use Sportal\FootballApi\Domain\Coach\ICoachEntityFactory;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;

class CoachTableMapper implements TableMapper
{
    private JoinFactory $joinFactory;

    private CountryTableMapper $countryMapper;

    private ICoachEntityFactory $coachEntityFactory;

    private RelationFactory $relationFactory;

    /**
     * CoachTableMapper constructor.
     * @param JoinFactory $joinFactory
     * @param CountryTableMapper $countryMapper
     * @param ICoachEntityFactory $coachEntityFactory
     * @param RelationFactory $relationFactory
     */
    public function __construct(JoinFactory $joinFactory, CountryTableMapper $countryMapper, ICoachEntityFactory $coachEntityFactory, RelationFactory $relationFactory)
    {
        $this->joinFactory = $joinFactory;
        $this->countryMapper = $countryMapper;
        $this->coachEntityFactory = $coachEntityFactory;
        $this->relationFactory = $relationFactory;
    }


    public function toEntity(array $data): ICoachEntity
    {
        return $this->coachEntityFactory->setEmpty()
            ->setId($data[CoachTable::FIELD_ID])
            ->setName($data[CoachTable::FIELD_NAME])
            ->setCountry($data[CoachTable::FIELD_COUNTRY])
            ->setCountryId($data[CoachTable::FIELD_COUNTRY_ID])
            ->setBirthdate(isset($data[CoachTable::FIELD_BIRTHDATE]) ? new DateTimeImmutable($data[CoachTable::FIELD_BIRTHDATE]) : null)
            ->setGender(isset($data[CoachTable::FIELD_GENDER]) ? new PersonGender($data[CoachTable::FIELD_GENDER]) :null)
            ->create();
    }

    public function getLeftJoin(): Join
    {
        return $this->joinFactory->createLeft(CoachTable::TABLE_NAME, CoachTable::getColumns())
            ->setFactory([$this, 'toEntity'])
            ->addChild($this->countryMapper->getInnerJoin());
    }

    public function getTableName(): string
    {
        return CoachTable::TABLE_NAME;
    }

    /**
     * @inheritDoc
     */
    public function getColumns(): array
    {
        return CoachTable::getColumns();
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [$this->relationFactory->from(CountryTableMapper::TABLE_NAME, RelationType::REQUIRED())->create()];
    }
}