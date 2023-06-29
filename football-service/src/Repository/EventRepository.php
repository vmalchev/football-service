<?php

namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Builder\MatchExpressionBuilder;
use Sportal\FootballApi\Cache\Index\GeneratesIndexName;
use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Database\Query\CompositeExpression;
use Sportal\FootballApi\Database\Query\Join;
use Sportal\FootballApi\Database\Query\Query;
use Sportal\FootballApi\Database\Query\SortDirection;
use Sportal\FootballApi\Domain\Match\MatchMinuteResolver;
use Sportal\FootballApi\Filter\MatchListingFilter;
use Sportal\FootballApi\Model\Event;
use Sportal\FootballApi\Model\ModelInterface;
use Sportal\FootballApi\Model\Round;
use Sportal\FootballApi\Model\RoundType;
use Sportal\FootballApi\Model\TeamScore;
use Sportal\FootballApi\Model\TournamentOrder;

class EventRepository extends Repository
{
    const MATCH_LIMIT = 3000;

    use GeneratesIndexName;

    private $db;

    private $stageRepository;

    private $teamRepository;

    private $statusRepository;

    private $venueRepository;

    private $refereeRepository;

    private $stageGroupRepository;

    private $orderRepository;

    private $tournamentOrderRepository;

    private MatchMinuteResolver $minuteResolver;

    public function __construct(
        Database $db,
        TournamentSeasonStageRepository $stageRepository,
        TeamRepository $teamRepository,
        EventStatusRepository $statusRepository,
        VenueRepository $venueRepository,
        RefereeRepository $refereeRepository,
        StageGroupRepository $stageGroupRepository,
        EventOrderRepository $orderRepository,
        TournamentOrderRepository $tournamentOrderRepository,
        MatchMinuteResolver $minuteResolver
    )
    {
        $this->db = $db;
        $this->stageRepository = $stageRepository;
        $this->teamRepository = $teamRepository;
        $this->statusRepository = $statusRepository;
        $this->venueRepository = $venueRepository;
        $this->refereeRepository = $refereeRepository;
        $this->stageGroupRepository = $stageGroupRepository;
        $this->orderRepository = $orderRepository;
        $this->tournamentOrderRepository = $tournamentOrderRepository;
        $this->minuteResolver = $minuteResolver;
    }

    /**
     *
     * @param array $eventArr
     * @return \Sportal\FootballApi\Model\Event
     */
    public function createObject($eventArr)
    {
        $event = new Event();
        $event->setTournamentSeasonStage($eventArr['tournament_season_stage'])
            ->setEventStatus($eventArr['event_status'])
            ->setStartTime(new \DateTime($eventArr['start_time']))
            ->setHomeTeamName($eventArr['home_name'])
            ->setAwayTeamName($eventArr['away_name'])
            ->setGoalHome($eventArr['goal_home'])
            ->setGoalAway($eventArr['goal_away'])
            ->setId($eventArr['id']);

        if (isset($eventArr['minute'])) {
            $event->setMinute($eventArr['minute']);
        }

        if (isset($eventArr['home_team'])) {
            $event->setHomeTeam($eventArr['home_team']);
        }

        if (isset($eventArr['away_team'])) {
            $event->setAwayTeam($eventArr['away_team']);
        }

        if (isset($eventArr['round']) || isset($eventArr['round_type_id'])) {
            $event->setRoundType(new RoundType($eventArr['round_type_id'], $eventArr['round']));
        }

        if (isset($eventArr['penalty_home']) && isset($eventArr['penalty_away'])) {
            $event->setPenaltyHome($eventArr['penalty_home']);
            $event->setPenaltyAway($eventArr['penalty_away']);
        }

        if (isset($eventArr['agg_home']) && isset($eventArr['agg_away'])) {
            $event->setAggHome($eventArr['agg_home']);
            $event->setAggAway($eventArr['agg_away']);
        }

        if (isset($eventArr['venue'])) {
            $event->setVenue($eventArr['venue']);
        }

        if (isset($eventArr['referee'])) {
            $event->setReferee($eventArr['referee']);
        }

        if (isset($eventArr['spectators'])) {
            $event->setSpectators($eventArr['spectators']);
        }

        if (isset($eventArr['incident_home']) && isset($eventArr['incident_away'])) {
            $event->setIncidents($eventArr['incident_home'] + $eventArr['incident_away']);
        }

        if (isset($eventArr['incidents'])) {
            $event->setIncidents($eventArr['incidents']);
        }

        if (isset($eventArr['stage_group'])) {
            $event->setStageGroup($eventArr['stage_group']);
        }

        if (isset($eventArr['lineup_available'])) {
            $event->setLineupAvaible($eventArr['lineup_available']);
        }

        if (isset($eventArr['live_updates'])) {
            $event->setLiveUpdates($eventArr['live_updates']);
        }

        if (isset($eventArr['teamstats_available'])) {
            $event->setTeamstatsAvailable($eventArr['teamstats_available']);
        }

        if (!empty($eventArr['updated_at'])) {
            $event->setUpdatedAt(new \DateTime($eventArr['updated_at']));
        }

        if (!empty($eventArr['home_score'])) {
            $event->setHomeScore(TeamScore::create($eventArr['home_score']));
        }

        if (!empty($eventArr['away_score'])) {
            $event->setAwayScore(TeamScore::create($eventArr['away_score']));
        }

        $event->setTimestamps($eventArr);

        $phaseStartedAt = (!empty($eventArr['started_at'])) ? new \DateTimeImmutable($eventArr['started_at'], new \DateTimeZone('UTC')) : null;
        $minute = $this->minuteResolver->resolve($event->getEventStatus()->getCode(), $phaseStartedAt);
        if (!is_null($minute)) {
            $event->setMinute($minute->getRegular());
        }

        return $event;
    }

    public function findByStage($stageId, $round = null, $sortDirection = SortDirection::ASC)
    {
        $query = $this->db->createQuery();
        $expr = $query->andX()->eq(Event::STAGE_INDEX, $stageId);
        if (!empty($round)) {
            $expr->eq(Event::ROUND_INDEX, $round);
        }
        $query->where($expr)->addOrderBy('start_time', $sortDirection);
        return $this->queryDatabase($query);
    }

    public function findRecentlyUpdated(\DateTime $after)
    {
        $query = $this->db->createQuery();
        $timestamp = $this->db->formatTime($after);
        $query->where($query->andX()
            ->gteq(Event::UPDATED_INDEX, $timestamp))
            ->addOrderBy(Event::UPDATED_INDEX, SortDirection::DESC);
        return $this->queryDatabase($query);
    }

    public function findInProgress()
    {
        $query = $this->db->createQuery();
        $statusIds = [];
        $statuses = $this->statusRepository->findAll();
        foreach ($statuses as $status) {
            if ($status->isLive()) {
                $statusIds[] = $status->getId();
            }
        }
        $expr = $query->andX()->in('event_status_id', $statusIds);
        $query->where($expr)->addOrderBy(Event::TIME_INDEX, SortDirection::ASC);
        return $this->queryDatabase($query);
    }

    public function findNotStarted(\DateTime $beforeTime)
    {
        $query = $this->db->createQuery()
            ->from($this->getModelClass())
            ->addJoin(new Join($this->statusRepository->getModelClass(), []));

        $expr = $query->andX()->lteq(Event::TIME_INDEX, $this->db->formatTime($beforeTime));
        foreach ($this->statusRepository->getNotStartedTypes() as $type) {
            $expr->eq('type', $type, $this->statusRepository->getModelClass());
        }

        $query->where($expr);
        return $this->db->executeQuery($query, function ($row) {
            return $row['id'];
        });
    }

    public function findByTime(\DateTime $from, \DateTime $to, $clientOrder = null)
    {
        $machListingFilter = (new MatchListingFilter())
            ->setFromStartTime(\DateTimeImmutable::createFromMutable($from))
            ->setToStartTime(\DateTimeImmutable::createFromMutable($to))
            ->setTournamentOrder($clientOrder);
        return $this->getMatches($machListingFilter, $clientOrder, SortDirection::ASC);
    }

    /**
     *
     * @param unknown $teamId
     * @param unknown $stageId
     * @return Event[]
     */
    public function findByTeam($teamId, \DateTime $from, \DateTime $to, $stageId = null, $direction = null)
    {
        $machListingFilter = (new MatchListingFilter())
            ->setFromStartTime(\DateTimeImmutable::createFromMutable($from))
            ->setToStartTime(\DateTimeImmutable::createFromMutable($to))
            ->setTeamIds([$teamId]);
        if (!empty($stageId)) {
            $machListingFilter->setStageIds([$stageId]);
        }
        if ($direction === null) {
            $direction = SortDirection::ASC;
        }
        return $this->getMatches($machListingFilter, null, $direction);
    }

    public function findByTeams($homeId, $awayId)
    {
        $query = $this->db->createQuery();
        $expr = $query->andX()
            ->eq("home_id", $homeId)
            ->eq("away_id", $awayId);
        $query->where($expr)->addOrderBy(Event::TIME_INDEX, SortDirection::ASC);
        return $this->queryDatabase($query);
    }

    public function getEventCount($stageId)
    {
        $query = $this->db->createQuery();
        $query->from('event', [
            'count(id) as count'
        ])->whereEquals(Event::STAGE_INDEX, $stageId);
        $result = $this->db->executeQuery($query, function ($row) {
            return $row['count'];
        });
        return $result[0];
    }

    /**
     *
     * @param unknown $homeId
     * @param unknown $awayId
     * @param \DateTime $startTime
     * @return Event[]
     */
    public function findMatchingByStage($homeId, $awayId, $stageId, $round)
    {
        $query = $this->db->createQuery();
        $expr = $this->createTeamMatchExpr($homeId, $awayId)
            ->eq(Event::STAGE_INDEX, $stageId)
            ->eq(Event::ROUND_INDEX, $round);
        $query->where($expr);
        return $this->queryDatabase($query);
    }

    /**
     *
     * @param unknown $homeId
     * @param unknown $awayId
     * @param \DateTime $startTime
     * @return Event[]
     */
    public function findMatchingByTime($homeId, $awayId, \DateTime $startTime)
    {
        $query = $this->db->createQuery();
        $expr = $this->createTeamMatchExpr($homeId, $awayId)->eq(Event::TIME_INDEX, $this->db->formatTime($startTime));
        $query->where($expr);
        return $this->queryDatabase($query);
    }

    /**
     *
     * @param integer $stageId
     * @param boolean $withEvents
     * @return \Sportal\FootballApi\Model\Round[]
     */
    public function getRounds($stageId, $withEvents = false)
    {
        $events = $this->findByStage($stageId);
        $rounds = [];
        foreach ($events as $event) {
            $roundName = $event->getRound();
            if (!empty(($roundName))) {
                if (!isset($rounds[$roundName])) {
                    $round = (new Round())->setRound($roundName);
                    if ($withEvents) {
                        $round->setShowEvents(true);
                    }
                    $rounds[$roundName] = $round;
                }
                $rounds[$roundName]->addEvent($event->clonePartial());
            }
        }

        return array_values($rounds);
    }

    /**
     *
     * @param integer $id
     * @return Event
     */
    public function find($id)
    {
        $query = $this->db->createQuery()->whereEquals('id', $id);
        $data = $this->queryDatabase($query);
        return (!empty($data)) ? $data[0] : null;
    }

    public function findLeaguesInProgress()
    {
        $events = $this->findInProgress();
        $groups = [];
        $stages = [];
        foreach ($events as $event) {
            if ($event->getStageGroup() !== null) {
                $groups[$event->getStageGroup()->getId()] = $event->getStageGroup();
            } else {
                $stage = $event->getTournamentSeasonStage();
                $stages[$stage->getId()] = $this->stageRepository->cloneObject($stage);
            }
        }
        return array_merge(array_values($stages), array_values($groups));
    }

    public function getTeamForm($teamId, $stageId = null)
    {
        $query = $this->db->createQuery()
            ->setMaxResults(TeamFormRepository::MAX_RESULTS)
            ->addOrderBy(Event::TIME_INDEX, 'desc');
        $expr = $query->andX()->add($this->createTeamExpr($teamId, $stageId));
        foreach ($this->statusRepository->getFinishedTypes() as $type) {
            $expr->eq('type', $type, $this->statusRepository->getModelClass());
        }

        $query->where($expr);
        return $this->queryDatabase($query);
    }

    /**
     *
     * @return \DateTime
     */
    public function getMinUpdatedAfter()
    {
        $dateTime = (new \DateTime());
        $dateTime->modify('-8 days');
        return $dateTime;
    }

    public function getJoinList($tournamentOrder = null)
    {
        $factory = $this->db->getJoinFactory();

        $joinArr = $this->stageRepository->getJoin();

        $countryJoin = $factory->createInner($joinArr[0]['className'], $joinArr[0]['columns']);
        $seasonJoin = $factory->createInner($joinArr[1]['className'], $joinArr[1]['columns']);

        $tournamentOrderJoin = null;
        if (!is_null($tournamentOrder)) {
            $tournamentOrderJoin = $factory->createInner(
                $this->tournamentOrderRepository->getModelClass(),
                $this->tournamentOrderRepository->getColumns(),
                'tournament_id',
            )->setReference(TournamentOrder::TOURNAMENT_INDEX);
        }

        return [
            $factory->createInner($this->statusRepository->getModelClass(), $this->statusRepository->getColumns()),
            $factory->createInner($this->stageRepository->getModelClass(), $this->stageRepository->getColumns())
                ->addChild($countryJoin)
                ->addChild(is_null($tournamentOrder) ? $seasonJoin : $seasonJoin->addChild($tournamentOrderJoin)),
            $factory->createLeft($this->teamRepository->getModelClass(), $this->teamRepository->getPartialColumns(),
                'home_id', 'home_team'),
            $factory->createLeft($this->teamRepository->getModelClass(), $this->teamRepository->getPartialColumns(),
                'away_id', 'away_team'),
            $factory->createLeft($this->stageGroupRepository->getModelClass(),
                $this->stageGroupRepository->getColumns()),
            $factory->createLeft($this->refereeRepository->getModelClass(), $this->refereeRepository->getColumns()),
            $factory->createLeft($this->venueRepository->getModelClass(), $this->venueRepository->getColumns())
        ];
    }

    public function getModelClass()
    {
        return Event::class;
    }

    public function buildObject(array $eventArr)
    {
        $eventArr['tournament_season_stage'] = $this->stageRepository->buildObject($eventArr['tournament_season_stage'],
            true);
        $eventArr['event_status'] = $this->statusRepository->createObject($eventArr['event_status']);

        if (isset($eventArr['home_team'])) {
            $eventArr['home_team'] = $this->teamRepository->createPartial($eventArr['home_team']);
        }

        if (isset($eventArr['away_team'])) {
            $eventArr['away_team'] = $this->teamRepository->createPartial($eventArr['away_team']);
        }

        if (isset($eventArr['venue'])) {
            $eventArr['venue'] = $this->venueRepository->createPartial($eventArr['venue']);
        }
        if (isset($eventArr['referee'])) {
            $eventArr['referee'] = $this->refereeRepository->createPartialObject($eventArr['referee']);
        }

        if (isset($eventArr['stage_group'])) {
            $eventArr['stage_group'] = $this->stageGroupRepository->createObject($eventArr['stage_group']);
        }

        return $this->createObject($eventArr);
    }

    public function updateHomeTeam($team, $eventId)
    {
        $event = $this->find($eventId);
        $existing = clone $event;
        if ($event !== null) {
            $event->setHomeTeam($this->teamRepository->clonePartial($team));
            $this->updateSingle($existing, $event);
        }
    }

    public function updateAwayTeam($team, $eventId)
    {
        $event = $this->find($eventId);
        $existing = clone $event;
        if ($event !== null) {
            $event->setAwayTeam($this->teamRepository->clonePartial($team));
            $this->updateSingle($existing, $event);
        }
    }

    public function updateVenue($venue, $eventId)
    {
        $event = $this->find($eventId);
        $existing = clone $event;
        if ($event !== null) {
            $event->setVenue($venue);
            $this->updateSingle($existing, $event);
        }
    }

    public function updateReferee($referee, $eventId)
    {
        $event = $this->find($eventId);
        $existing = clone $event;
        if ($event !== null) {
            $event->setReferee($referee);
            $this->updateSingle($existing, $event);
        }
    }

    public function setLineupAvailable($eventId)
    {
        $event = $this->find($eventId);
        $existing = clone $event;
        if ($event !== null) {
            $event->setLineupAvaible(true);
            $this->updateSingle($existing, $event);
        }
    }

    public function setTeamstatsAvailable(Event $existing)
    {
        $updated = clone $existing;
        $updated->setTeamstatsAvailable(true);
        $this->updateSingle($existing, $updated);
    }

    public function create(ModelInterface $model)
    {
        $this->db->insert($model);
        $this->db->flush();
    }

    public function patchExisting(ModelInterface $existing, ModelInterface $updated)
    {
        $updated->setId($existing->getId());
        if ($existing->getLineupAvaible() && $updated->getLineupAvaible() === null) {
            $updated->setLineupAvaible(true);
        }
        if ($existing->getTeamstatsAvailable() && $updated->getTeamstatsAvailable() === null) {
            $updated->setTeamstatsAvailable(true);
        }
        return $updated;
    }

    public function update(ModelInterface $model)
    {
        $this->db->update($model);
        $this->db->flush();
    }

    public function delete(ModelInterface $model)
    {
        $this->db->delete($model);
        $this->db->flush();
    }

    protected function queryDatabase(Query $query)
    {
        $query->from($this->getModelClass())
            ->addJoinList($this->getJoinList());
        return $this->db->executeQuery($query, [
            $this,
            'buildObject'
        ]);
    }

    /**
     *
     * @param integer $teamId
     * @param integer $stageId
     * @return \Sportal\FootballApi\Database\Query\CompositeExpression
     */
    protected function createTeamExpr($teamId, $stageId = null)
    {
        $query = $this->db->createQuery();
        $expr = $query->orX()
            ->eq('home_id', $teamId)
            ->eq('away_id', $teamId);

        if ($stageId !== null) {
            $expr = $query->andX()
                ->add($expr)
                ->eq(Event::STAGE_INDEX, $stageId);
        }

        return $expr;
    }

    protected function createTeamMatchExpr($homeId, $awayId)
    {
        return $this->db->createQuery()
            ->andX()
            ->eq('home_id', $homeId)
            ->eq('away_id', $awayId);
    }

    private function updateSingle(Event $existing, Event $event)
    {
        $this->db->update($event);
        $this->db->flush();
    }

    protected function getIgnoredKeys()
    {
        $ignored = parent::getIgnoredKeys();
        $ignored[] = 'lineup_available';
        $ignored[] = 'teamstats_available';
        return $ignored;
    }

    public function findAll($filter = [])
    {
    }

    public function getMatches(
        MatchListingFilter $matchListingFilter,
        $tournamentOrder = null,
        $sortDirection = null,
        $offset = null,
        $limit = null
    )
    {
        $query = $this->db->createQuery();

        $query->from($this->getModelClass())
            ->addJoinList($this->getJoinList($tournamentOrder))
            ->where((new MatchExpressionBuilder($matchListingFilter, new CompositeExpression(CompositeExpression::TYPE_AND), $this->stageRepository, $this->statusRepository))
                ->build())
            ->offset($offset)
            ->limit($limit);

        if ($tournamentOrder !== null) {
            $query->addOrderBy('sortorder', SortDirection::ASC, TournamentOrder::class)
                ->addOrderBy('start_time', !empty($sortDirection) ? $sortDirection : SortDirection::DESC);
        } else {
            $query->addOrderBy('start_time', !empty($sortDirection) ? $sortDirection : SortDirection::DESC);
        }
        return $this->db->executeQuery($query, [
            $this,
            'buildObject'
        ]);
    }
}
