<?php


namespace Sportal\FootballApi\Infrastructure\Team;


use Sportal\FootballApi\Domain\Team\ITeamEntityFactory;
use Sportal\FootballApi\Domain\Team\TeamGender;
use Sportal\FootballApi\Infrastructure\Country\CountryTableMapper;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\President\PresidentTableMapper;
use Sportal\FootballApi\Infrastructure\Venue\VenueTableMapper;

class TeamTableMapper implements TableMapper
{

    const TABLE_NAME = "team";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_THREE_LETTER_CODE = "three_letter_code";
    const FIELD_GENDER = "gender";
    const FIELD_SHORT_NAME = "short_name";
    const FIELD_NATIONAL = "national";
    const FIELD_UNDECIDED = "undecided";
    const FIELD_COUNTRY_ID = "country_id";
    const FIELD_VENUE_ID = "venue_id";
    const FIELD_PRESIDENT_ID = "president_id";
    const FIELD_SOCIAL = "social";
    const FIELD_PROFILE = "profile";

    private ITeamEntityFactory $factory;
    private RelationFactory $relationFactory;

    /**
     * TeamTableMapper constructor.
     * @param ITeamEntityFactory $factory
     * @param RelationFactory $relationFactory
     */
    public function __construct(ITeamEntityFactory $factory,
                                RelationFactory $relationFactory)
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
            self::FIELD_THREE_LETTER_CODE,
            self::FIELD_GENDER,
            self::FIELD_SHORT_NAME,
            self::FIELD_NATIONAL,
            self::FIELD_UNDECIDED,
            self::FIELD_COUNTRY_ID,
            self::FIELD_VENUE_ID,
            self::FIELD_PRESIDENT_ID,
            self::FIELD_SOCIAL,
            self::FIELD_PROFILE
        ];
    }

    public function toEntity(array $data): object
    {
        $factory = $this->factory->setEmpty();
        $factory->setId($data[TeamTable::FIELD_ID]);
        $factory->setName($data[TeamTable::FIELD_NAME]);
        $factory->setThreeLetterCode($data[TeamTable::FIELD_THREE_LETTER_CODE]);
        $factory->setGender(isset($data[TeamTable::FIELD_GENDER]) ? new TeamGender($data[TeamTable::FIELD_GENDER]) : null);
        $factory->setShortName($data[TeamTable::FIELD_SHORT_NAME] ?? null);
        $factory->setIsUndecided($data[TeamTable::FIELD_UNDECIDED]);
        $factory->setIsNational($data[TeamTable::FIELD_NATIONAL]);
        $factory->setCountryId($data[TeamTable::FIELD_COUNTRY_ID] ?? null);
        $factory->setCountry($data[TeamTable::FIELD_COUNTRY] ?? null);
        $factory->setVenueId($data[TeamTable::FIELD_VENUE_ID] ?? null);
        $factory->setVenue($data[TeamTable::FIELD_VENUE] ?? null);
        $factory->setPresidentId($data[TeamTable::FIELD_PRESIDENT_ID] ?? null);
        $factory->setPresident($data[TeamTable::FIELD_PRESIDENT] ?? null);
        $factory->setSocial(isset($data[TeamTable::FIELD_SOCIAL]) ? TeamSocialEntity::fromArray(
            json_decode($data[TeamTable::FIELD_SOCIAL], true)
        ) : null);
        $factory->setProfile(isset($data[TeamTable::FIELD_PROFILE]) ? TeamProfileEntity::fromArray(
            json_decode($data[TeamTable::FIELD_PROFILE], true)
        ) : null);
        $factory->setTeamColors($data[TeamColorsTable::TABLE_NAME] ?? null);

        return $factory->create();
    }

    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(CountryTableMapper::TABLE_NAME, RelationType::REQUIRED())->create(),
            $this->relationFactory->from(VenueTableMapper::TABLE_NAME, RelationType::OPTIONAL())->create(),
            $this->relationFactory->from(PresidentTableMapper::TABLE_NAME, RelationType::OPTIONAL())->create()
        ];
    }
}