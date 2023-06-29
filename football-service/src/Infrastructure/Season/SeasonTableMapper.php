<?php


namespace Sportal\FootballApi\Infrastructure\Season;

use Sportal\FootballApi\Domain\Season\ISeasonEntity;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Query\Join;
use Sportal\FootballApi\Infrastructure\Database\Query\JoinFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Tournament\TournamentTableMapper;

class SeasonTableMapper implements TableMapper
{
    const TABLE_NAME = "tournament_season";

    const FIELD_ID = "id";
    const FIELD_NAME = "name";
    const FIELD_TOURNAMENT_ID = "tournament_id";
    const FIELD_TOURNAMENT = "tournament";
    const FIELD_ACTIVE = "active";

    const FIELD_UPDATED_AT = "updated_at";

    private TournamentTableMapper $tournamentMapper;

    private TournamentSeasonTeamTableMapper $tournamentSeasonTeamMapper;

    private SeasonEntityFactory $factory;

    private JoinFactory $joinFactory;

    private RelationFactory $relationFactory;

    /**
     * @param TournamentTableMapper $tournamentMapper
     * @param SeasonEntityFactory $factory
     * @param TournamentSeasonTeamTableMapper $tournamentSeasonTeamMapper
     * @param JoinFactory $joinFactory
     * @param RelationFactory $relationFactory
     */
    public function __construct(TournamentTableMapper $tournamentMapper,
                                SeasonEntityFactory $factory,
                                TournamentSeasonTeamTableMapper $tournamentSeasonTeamMapper,
                                JoinFactory $joinFactory, RelationFactory $relationFactory)
    {
        $this->tournamentMapper = $tournamentMapper;
        $this->factory = $factory;
        $this->tournamentSeasonTeamMapper = $tournamentSeasonTeamMapper;
        $this->joinFactory = $joinFactory;
        $this->relationFactory = $relationFactory;
    }

    public function getColumns(): array
    {
        return [
            self::FIELD_ID,
            self::FIELD_NAME,
            self::FIELD_TOURNAMENT_ID,
            self::FIELD_ACTIVE,
        ];
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }


    public function toEntity(array $data): ISeasonEntity
    {
        return $this->factory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setName($data[self::FIELD_NAME])
            ->setTournamentId($data[self::FIELD_TOURNAMENT_ID])
            ->setTournament($data[self::FIELD_TOURNAMENT] ?? null)
            ->setStatus(StatusDatabaseConverter::fromValue($data[self::FIELD_ACTIVE]))
            ->create();
    }

    public function getInnerJoin(): Join
    {
        return $this->joinFactory->createInner(self::TABLE_NAME, $this->getColumns())
            ->setFactory([$this, 'toEntity'])
            ->addChild($this->tournamentMapper->getInnerJoin());
    }

    public function joinTournament(): Join
    {
        return $this->tournamentMapper->getInnerJoin();
    }

    public function joinTournamentSeasonTeam(): Join
    {
        return $this->tournamentSeasonTeamMapper->getInnerJoin();
    }

    /**
     * @inheritDoc
     */
    public function getRelations(): ?array
    {
        return [
            $this->relationFactory->from(TournamentTableMapper::TABLE_NAME, RelationType::REQUIRED())->create()
        ];
    }
}