<?php


namespace Sportal\FootballApi\Infrastructure\TeamSquad;


use Sportal\FootballApi\Infrastructure\Database\Converter\DateConverter;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Player\PlayerTableMapper;
use Sportal\FootballApi\Infrastructure\Team\TeamEntityFactory;
use Sportal\FootballApi\Infrastructure\Team\TeamTableMapper;

class TeamPlayerTableMapper
{
    const FIELD_ID = "id";
    const FIELD_TEAM_ID = "team_id";
    const FIELD_TEAM = "team";
    const FIELD_PLAYER_ID = "player_id";
    const FIELD_ACTIVE = "active";
    const FIELD_LOAN = "loan";
    const FIELD_START_DATE = "start_date";
    const FIELD_END_DATE = "end_date";
    const FIELD_SHIRT_NUMBER = "shirt_number";
    const TABLE_NAME = "team_player";
    const FIELD_PLAYER = "player";

    private PlayerTableMapper $playerMapper;
    private TeamPlayerEntityFactory $factory;
    private RelationFactory $relationFactory;
    private TeamEntityFactory $teamEntityFactory;

    /**
     * TeamPlayerTableMapper constructor.
     * @param PlayerTableMapper $playerMapper
     * @param TeamPlayerEntityFactory $factory
     * @param RelationFactory $relationFactory
     * @param TeamEntityFactory $teamEntityFactory
     */
    public function __construct(PlayerTableMapper $playerMapper,
                                TeamPlayerEntityFactory $factory,
                                RelationFactory $relationFactory,
                                TeamEntityFactory $teamEntityFactory)
    {
        $this->playerMapper = $playerMapper;
        $this->factory = $factory;
        $this->relationFactory = $relationFactory;
        $this->teamEntityFactory = $teamEntityFactory;
    }

    public function create(array $data)
    {
        $entity = $this->factory->setEmpty()
            ->setPlayerId($data[self::FIELD_PLAYER_ID])
            ->setPlayer($data[self::FIELD_PLAYER] ?? null)
            ->setTeamId($data[self::FIELD_TEAM_ID])
            ->setTeam($data[self::FIELD_TEAM] ?? null)
            ->setStatus(StatusDatabaseConverter::fromValue($data[self::FIELD_ACTIVE]))
            ->setContractType(ContractTypeDatabaseConverter::fromValue($data[self::FIELD_LOAN]))
            ->setStartDate(DateConverter::fromValue($data[self::FIELD_START_DATE]))
            ->setEndDate(DateConverter::fromValue($data[self::FIELD_END_DATE]))
            ->setShirtNumber($data[self::FIELD_SHIRT_NUMBER] ?? null)
            ->create();
        if (isset($data[self::FIELD_ID])) {
            return $entity->withId($data[self::FIELD_ID]);
        } else {
            return $entity;
        }
    }

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function joinPlayer(): Join
    {
        return $this->playerMapper->getInnerJoin();
    }

    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(TeamTableMapper::TABLE_NAME, RelationType::REQUIRED())->create()
        ];
    }
}