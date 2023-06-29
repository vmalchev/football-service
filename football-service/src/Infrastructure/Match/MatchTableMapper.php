<?php


namespace Sportal\FootballApi\Infrastructure\Match;


use Closure;
use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Sportal\FootballApi\Domain\Match\IMatchEntityFactory;
use Sportal\FootballApi\Domain\Match\IMatchRefereeEntityFactory;
use Sportal\FootballApi\Domain\Match\MatchRefereeRole;
use Sportal\FootballApi\Domain\MatchStatus\IMatchStatusEntity;
use Sportal\FootballApi\Domain\MatchStatus\MatchStatusType;
use Sportal\FootballApi\Domain\Person\PersonGender;
use Sportal\FootballApi\Infrastructure\Database\Converter\DateTimeConverter;
use Sportal\FootballApi\Infrastructure\Database\Mapper\TableMapper;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationFactory;
use Sportal\FootballApi\Infrastructure\Database\Relation\RelationType;
use Sportal\FootballApi\Infrastructure\Group\GroupTableMapper;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchCoverageConverter;
use Sportal\FootballApi\Infrastructure\Match\Converter\MatchScoreConverter;
use Sportal\FootballApi\Infrastructure\MatchStatus\MatchStatusTableMapper;
use Sportal\FootballApi\Infrastructure\Referee\RefereeTable;
use Sportal\FootballApi\Infrastructure\Round\RoundTableMapper;
use Sportal\FootballApi\Infrastructure\Stage\StageTableMapper;
use Sportal\FootballApi\Infrastructure\Team\TeamColorsTable;
use Sportal\FootballApi\Infrastructure\Team\TeamTable;
use Sportal\FootballApi\Infrastructure\Venue\VenueTable;

class MatchTableMapper implements TableMapper
{
    const TABLE_NAME = 'event';

    const FIELD_ID = 'id';
    const FIELD_STATUS_ID = 'event_status_id';
    const FIELD_STATUS = 'event_status';
    const FIELD_STAGE_ID = 'tournament_season_stage_id';
    const FIELD_STAGE = 'tournament_season_stage';
    const FIELD_KICKOFF_TIME = 'start_time';
    const FIELD_GROUP_ID = 'stage_group_id';
    const FIELD_GROUP = 'stage_group';
    const FIELD_ROUND_KEY = 'round';
    const FIELD_ROUND = 'round_type';
    const FIELD_HOME_TEAM_ID = 'home_id';
    const FIELD_AWAY_TEAM_ID = 'away_id';
    const FIELD_HOME_TEAM = 'home_team';
    const FIELD_AWAY_TEAM = 'away_team';
    const FIELD_VENUE_ID = 'venue_id';
    const FIELD_VENUE = 'venue';
    const FIELD_COVERAGE = 'live_updates';
    const FIELD_SPECTATORS = 'spectators';
    const FIELD_REFEREES = 'referees';
    const FIELD_REFEREE_ID = 'referee_id';
    const FIELD_PHASE_STARTED_AT = 'started_at';
    const FIELD_FINISHED_AT = 'finished_at';
    const FIELD_ROUND_TYPE_ID = 'round_type_id';

    // fields kept for legacy purpose (v1 implementation)
    const FIELD_HOME_TEAM_NAME = 'home_name';
    const FIELD_AWAY_TEAM_NAME = 'away_name';


    private IMatchEntityFactory $entityFactory;
    private RelationFactory $relationFactory;
    private IMatchRefereeEntityFactory $matchRefereeFactory;
    private MatchScoreConverter $matchScoreConverter;
    private TeamColorsTableMapper $colorsTableMapper;

    /**
     * MatchTableMapper constructor.
     * @param IMatchEntityFactory $entityFactory
     * @param RelationFactory $relationFactory
     * @param IMatchRefereeEntityFactory $matchRefereeFactory
     * @param MatchScoreConverter $matchScoreConverter
     * @param TeamColorsTableMapper $colorsTableMapper
     */
    public function __construct(IMatchEntityFactory           $entityFactory,
                                RelationFactory               $relationFactory,
                                IMatchRefereeEntityFactory    $matchRefereeFactory,
                                Converter\MatchScoreConverter $matchScoreConverter,
                                TeamColorsTableMapper         $colorsTableMapper)
    {
        $this->entityFactory = $entityFactory;
        $this->relationFactory = $relationFactory;
        $this->matchRefereeFactory = $matchRefereeFactory;
        $this->matchScoreConverter = $matchScoreConverter;
        $this->colorsTableMapper = $colorsTableMapper;
    }

    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public function getColumns(): array
    {
        return [];
    }

    public function getRelations(): ?array
    {
        $matchColorsRelation = $this->colorsTableMapper->getMatchColorsRelation();

        $homeTeamRelation = $this->relationFactory->from(TeamTable::TABLE_NAME, RelationType::OPTIONAL())
            ->setForeignKey(self::FIELD_HOME_TEAM_ID)
            ->setObjectKey(self::FIELD_HOME_TEAM)
            ->addChild($this->colorsTableMapper->getHomeTeamColorsRelation())
            ->create();

        $awayTeamRelation = $this->relationFactory->from(TeamTable::TABLE_NAME, RelationType::OPTIONAL())
            ->setForeignKey(self::FIELD_AWAY_TEAM_ID)
            ->setObjectKey(self::FIELD_AWAY_TEAM)
            ->addChild($this->colorsTableMapper->getAwayTeamColorsRelation())
            ->create();

        $round_type_relation = $this->relationFactory->from(RoundTableMapper::TABLE_NAME, RelationType::OPTIONAL())
            ->setObjectKey(self::FIELD_ROUND)
            ->create();

        return [
            $this->relationFactory->from(MatchStatusTableMapper::TABLE_NAME, RelationType::REQUIRED())->create(),
            $this->relationFactory->from(StageTableMapper::TABLE_NAME, RelationType::REQUIRED())->create(),
            $this->relationFactory->from(GroupTableMapper::TABLE_NAME, RelationType::OPTIONAL())->create(),
            $round_type_relation,
            $matchColorsRelation,
            $homeTeamRelation,
            $awayTeamRelation,
            $this->relationFactory->from(RefereeTable::TABLE_NAME, RelationType::OPTIONAL())
                ->setColumns([RefereeTable::FIELD_ID, RefereeTable::FIELD_NAME, RefereeTable::FIELD_GENDER])
                ->setObjectFactory(Closure::fromCallable(function ($refereeData) {
                    return [
                        $this->matchRefereeFactory
                            ->setRefereeId($refereeData[RefereeTable::FIELD_ID])
                            ->setRefereeName($refereeData[RefereeTable::FIELD_NAME])
                            ->setRole(MatchRefereeRole::REFEREE())
                            ->setGender(!is_null($refereeData[RefereeTable::FIELD_GENDER])
                                ? new PersonGender($refereeData[RefereeTable::FIELD_GENDER]) : null)
                            ->create()
                    ];
                }))
                ->setObjectKey(self::FIELD_REFEREES)
                ->create(),
            $this->relationFactory->from(VenueTable::TABLE_NAME, RelationType::OPTIONAL())
                ->withoutChildren()
                ->create()
        ];
    }

    public function toEntity(array $data): IMatchEntity
    {
        /** @var IMatchStatusEntity $status */
        $status = $data[self::FIELD_STATUS] ?? null;
        $score = $this->matchScoreConverter->fromValue($data);
        if ($status !== null
            && ($status->getType()->equals(MatchStatusType::NOT_STARTED()) || $status->getType()->equals(MatchStatusType::CANCELLED()))) {
            $score = null;
        }
        return $this->entityFactory->setEmpty()
            ->setId($data[self::FIELD_ID])
            ->setKickoffTime(DateTimeConverter::fromValue($data[self::FIELD_KICKOFF_TIME]))
            ->setStatusId($data[self::FIELD_STATUS_ID])
            ->setStatus($status)
            ->setStageId($data[self::FIELD_STAGE_ID])
            ->setStage($data[self::FIELD_STAGE] ?? null)
            ->setGroupId($data[self::FIELD_GROUP_ID])
            ->setGroup($data[self::FIELD_GROUP] ?? null)
            ->setRound($data[self::FIELD_ROUND] ?? null)
            ->setRoundKey($data[self::FIELD_ROUND_KEY])
            ->setHomeTeamId($data[self::FIELD_HOME_TEAM_ID])
            ->setAwayTeamId($data[self::FIELD_AWAY_TEAM_ID])
            ->setHomeTeam($data[self::FIELD_HOME_TEAM] ?? null)
            ->setAwayTeam($data[self::FIELD_AWAY_TEAM] ?? null)
            ->setVenueId($data[self::FIELD_VENUE_ID])
            ->setVenue($data[self::FIELD_VENUE] ?? null)
            ->setSpectators($data[self::FIELD_SPECTATORS])
            ->setReferees($data[self::FIELD_REFEREES] ?? null)
            ->setCoverage(MatchCoverageConverter::fromValue($data[self::FIELD_COVERAGE]))
            ->setPhaseStartedAt(DateTimeConverter::fromValue($data[self::FIELD_PHASE_STARTED_AT]))
            ->setFinishedAt(DateTimeConverter::fromValue($data[self::FIELD_FINISHED_AT]))
            ->setColors($data[TeamColorsTable::TABLE_NAME] ?? null)
            ->setScore($score)
            ->setRoundTypeId($data[self::FIELD_ROUND_TYPE_ID])
            ->create();
    }
}