<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Domain\Match\IMatchEntity;
use Swagger\Annotations as SWG;
use Sportal\FootballApi\Cache\Index\IndexableInterface;
use Sportal\FootballApi\Cache\Index\GeneratesIndexName;
use Sportal\FootballApi\Database\SurrogateKeyInterface as SurrogateInterface;

/**
 *
 * @SWG\Definition(definition="EventsByDate", required={"date", "items"},
 *      @SWG\Property(property="date", type="string", format="date", example="2016-08-13"),
 *      @SWG\Property(property="items", type="array", @SWG\Items(ref="#/definitions/Event"))
 *      )
 */

/**
 * Event
 * @SWG\Definition(required={"id", "start_time", "home_team", "away_team", "event_status", "tournament_season_stage"})
 */
class Event extends PartialEvent implements SurrogateInterface, IndexableInterface, SurrogateKeyInterface
{
    use GeneratesIndexName;

    const TIME_INDEX_STEP = '168 hours';

    const STAGE_INDEX = "tournament_season_stage_id";

    const ROUND_INDEX = "round";

    const TEAM_INDEX = 'team';

    const TIME_INDEX = 'start_time';

    const UPDATED_INDEX = 'updated_at';

    const LIVE_INDEX = 'live';

    const TEAM_HOME_INDEX = 'team_home';

    const TEAM_AWAY_INDEX = 'team_away';

    const BY_TEAMS = 'by_teams:';

    const TYPES = ['match'];
    
    use ContainsTimestamps;

    private static $timestampsNames = [
        'finished_at',
        'started_at'
    ];

    /**
     * Number of spectators at the event
     * @var integer
     * @SWG\Property(example=95554)
     */
    private $spectators;

    /**
     * TournamentSeasonStage in which the Event is part of. Should be used for Standing unless the Event is part of a StageGroup
     * @var \Sportal\FootballApi\Model\PartialStage
     * @SWG\Property(property="tournament_season_stage")
     */
    private $tournamentSeasonStage;

    /**
     * Live minute ticker. Only available if the event_status.type is 'inprogress'
     * @var integer
     * @SWG\Property(example=85)
     */
    private $minute;

    /**
     * Describes the group in which the Event is part of. Available if the Event is from a TournamentSeasonStage which has groups. Example: Champions League Group Stage -> Group A
     * @var \Sportal\FootballApi\Model\StageGroup
     * @SWG\Property(property="stage_group")
     */
    private $stageGroup;

    /**
     * @var \Sportal\FootballApi\Model\PartialPerson
     * @SWG\Property()
     */
    private $referee;

    /**
     * @var \Sportal\FootballApi\Model\Venue
     * @SWG\Property()
     */
    private $venue;

    /**
     * Number of incidents which have occured in the Event (goals, red cards, yellow cards, etc).
     * @var integer
     * @SWG\Property()
     */
    private $incidents = 0;

    /**
     * Indicates whether player lineup information is available or not
     * @var boolean
     * @SWG\Property(property="lineup_available")
     */
    private $lineupAvaible;

    /**
     * Indicates whether the event has livescore updates. If false the event will be updated after FT.
     * @var boolean
     * @SWG\Property(property="live_updates")
     */
    private $liveUpdates;

    /**
     * Indicates whether teamstats are available for this event
     * @var boolean
     * @SWG\Property(property="teamstats_available")
     */
    private $teamstatsAvailable;

    /**
     * Score information at various stages of the Event for Home Team
     * @var \Sportal\FootballApi\Model\TeamScore
     * @SWG\Property(property="home_score")
     */
    private $homeScore;

    /**
     * Score information at various stages of the Event for Home Team
     * @var \Sportal\FootballApi\Model\TeamScore
     * @SWG\Property(property="away_score")
     */
    private $awayScore;

    /**
     * @SWG\Property(property="started_at", type="string", format="date-time", description="timestamp when the current phase (1st_half, 2nd_half, et, etc) has started. Can be used for calculating the minute ticker")
     */
    
    /**
     * Name of the home team
     * @var string
     */
    private $homeTeamName;

    /**
     * Name of the away team
     * @var string
     */
    private $awayTeamName;

    /**
     *
     * @var \DateTime
     */
    private $updatedAt;

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Set spectators
     *
     * @param integer $spectators
     *
     * @return Event
     */
    public function setSpectators($spectators)
    {
        $this->spectators = (int) $spectators;
        
        return $this;
    }

    /**
     * Get spectators
     *
     * @return integer
     */
    public function getSpectators()
    {
        return $this->spectators;
    }

    /**
     * Set temHomeName
     *
     * @param string $teamHome
     *
     * @return Event
     */
    public function setHomeTeamName($teamHome)
    {
        $this->homeTeamName = $teamHome;
        
        return $this;
    }

    /**
     * Get homeTeamName
     *
     * @return string
     */
    public function getHomeTeamName()
    {
        return $this->homeTeamName;
    }

    /**
     * Set teamAway
     *
     * @param string $teamAway
     *
     * @return Event
     */
    public function setAwayTeamName($teamAway)
    {
        $this->awayTeamName = $teamAway;
        
        return $this;
    }

    /**
     * Get teamTwo
     *
     * @return string
     */
    public function getAwayTeamName()
    {
        return $this->awayTeamName;
    }

    /**
     * Set incidents
     *
     * @param integer $incidents
     *
     * @return Event
     */
    public function setIncidents($incidents)
    {
        $this->incidents = $incidents;
        
        return $this;
    }

    /**
     * Get incidents
     *
     * @return integer
     */
    public function getIncidents()
    {
        return $this->incidents;
    }

    /**
     * Set referee
     *
     * @param \Sportal\FootballApi\Model\Referee $referee
     *
     * @return Event
     */
    public function setReferee(\Sportal\FootballApi\Model\PartialPerson $referee = null)
    {
        $this->referee = $referee;
        
        return $this;
    }

    /**
     * Get referee
     *
     * @return \Sportal\FootballApi\Model\Referee
     */
    public function getReferee()
    {
        return $this->referee;
    }

    /**
     * Set tournamentSeasonStage
     *
     * @param \Sportal\FootballApi\Model\PartialStage $tournamentSeasonStage
     *
     * @return Event
     */
    public function setTournamentSeasonStage(\Sportal\FootballApi\Model\PartialStage $tournamentSeasonStage = null)
    {
        $this->tournamentSeasonStage = $tournamentSeasonStage;
        
        return $this;
    }

    /**
     * Get tournamentSeasonStage
     *
     * @return \Sportal\FootballApi\Model\PartialStage
     */
    public function getTournamentSeasonStage()
    {
        return $this->tournamentSeasonStage;
    }

    /**
     * Set venue
     *
     * @param \Sportal\FootballApi\Model\Venue $venue
     *
     * @return Event
     */
    public function setVenue(\Sportal\FootballApi\Model\Venue $venue = null)
    {
        $this->venue = $venue;
        
        return $this;
    }

    /**
     * Get venue
     *
     * @return \Sportal\FootballApi\Model\Venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @return integer
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param integer $minute
     */
    public function setMinute($minute)
    {
        $this->minute = (int) $minute;
        return $this;
    }

    public function getName()
    {
        return $this->homeTeamName . "-" . $this->awayTeamName;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        $data = [
            'tournament_season_stage_id' => $this->tournamentSeasonStage->getId(),
            'event_status_id' => $this->getEventStatus()->getId(),
            'start_time' => $this->getStartTime()->format("Y-m-d H:i:s"),
            'round' => $this->getRound(),
            'venue_id' => $this->venue !== null ? $this->venue->getId() : null,
            'referee_id' => $this->referee !== null ? $this->referee->getId() : null,
            'spectators' => $this->spectators,
            'home_id' => $this->getHomeId(),
            'away_id' => $this->getAwayId(),
            'home_name' => $this->homeTeamName,
            'away_name' => $this->awayTeamName,
            'goal_home' => $this->getGoalHome(),
            'goal_away' => $this->getGoalAway(),
            'penalty_home' => $this->getPenaltyHome(),
            'penalty_away' => $this->getPenaltyAway(),
            'agg_home' => $this->getAggHome(),
            'agg_away' => $this->getAggAway(),
            'incidents' => $this->incidents,
            'stage_group_id' => ($this->stageGroup !== null) ? $this->stageGroup->getId() : null,
            'lineup_available' => $this->lineupAvaible,
            'live_updates' => $this->liveUpdates,
            'teamstats_available' => $this->teamstatsAvailable,
            'home_score' => ($this->homeScore !== null) ? json_encode($this->homeScore) : null,
            'away_score' => ($this->awayScore !== null) ? json_encode($this->awayScore) : null,
            'round_type_id' => $this->getRoundType() !== null? $this->getRoundType()->getId() : null
        ];
        
        return array_merge($data, $this->getPersistanceTimestamps());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return array(
            'id' => $this->getId()
        );
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        
        $data['tournament_season_stage'] = $this->tournamentSeasonStage;
        if ($this->incidents > 0) {
            $data['incidents'] = $this->incidents;
        }
        
        if ($data['home_team'] === null) {
            $data['home_team'] = [
                'name' => $this->homeTeamName
            ];
        }
        if ($data['away_team'] === null) {
            $data['away_team'] = [
                'name' => $this->awayTeamName
            ];
        }
        
        if ($this->homeScore !== null) {
            $data['home_score'] = $this->homeScore;
        }
        
        if ($this->awayScore !== null) {
            $data['away_score'] = $this->awayScore;
        }
        
        if ($this->venue !== null) {
            $data['venue'] = $this->venue;
        }
        
        if ($this->referee !== null) {
            $data['referee'] = $this->referee;
        }
        
        if ($this->spectators !== null) {
            $data['spectators'] = $this->spectators;
        }
        
        if ($this->minute !== null) {
            $data['minute'] = $this->minute;
        }
        
        if ($this->stageGroup !== null) {
            $data['stage_group'] = $this->stageGroup;
        }
        
        if ($this->lineupAvaible !== null) {
            $data['lineup_available'] = $this->lineupAvaible;
        }
        
        if ($this->liveUpdates !== null) {
            $data['live_updates'] = $this->liveUpdates;
        }
        
        if ($this->updatedAt !== null) {
            $data['updated_at'] = $this->updatedAt->format(\DateTime::ATOM);
        }
        
        if ($this->teamstatsAvailable !== null) {
            $data['teamstats_available'] = $this->teamstatsAvailable;
        }
        
        return array_merge($data, $this->getTimestamps());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $objects = parent::getMlContentModels();
        $objects[] = $this->tournamentSeasonStage;
        
        if ($this->tournamentSeasonStage->getCountry() !== null) {
            $objects[] = $this->tournamentSeasonStage->getCountry();
        }
        
        if ($this->venue !== null) {
            $objects[] = $this->venue;
        }
        
        if ($this->referee !== null) {
            $objects[] = $this->referee;
        }

        if ($this->stageGroup !== null) {
            $objects[] = $this->stageGroup;
        }
        
        return $objects;
    }

    /**
     * @return \Sportal\FootballApi\Model\StageGroup
     */
    public function getStageGroup()
    {
        return $this->stageGroup;
    }

    /**
     * @param \Sportal\FootballApi\Model\StageGroup $stageGroup
     */
    public function setStageGroup(StageGroup $stageGroup = null)
    {
        $this->stageGroup = $stageGroup;
        return $this;
    }

    public function getLineupAvaible()
    {
        return $this->lineupAvaible;
    }

    public function setLineupAvaible($lineupAvaible)
    {
        $this->lineupAvaible = (boolean) $lineupAvaible;
        return $this;
    }

    public function getTimestampNames()
    {
        return static::$timestampsNames;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\IndexableInterface::getSortedIndecies()
     */
    public function getSortedIndecies()
    {
        $startTime = $this->getStartTime();
        $stageIndex = $this->getColumnIndex(static::STAGE_INDEX, $this->tournamentSeasonStage->getId());
        
        $indexes = array_merge($this->getTimeIndexes(), [
            $stageIndex => $startTime
        ]);
        
        if ($this->getEventStatus()->isLive()) {
            $indexes[static::LIVE_INDEX] = $startTime;
        }
        
        if ($this->updatedAt !== null) {
            $indexes[static::UPDATED_INDEX] = $this->updatedAt;
        }
        
        $homeId = $this->getHomeId();
        if (! empty($homeId)) {
            $indexes[$this->getColumnIndex(static::TEAM_INDEX, $homeId)] = $startTime;
            $multi = [
                static::TEAM_INDEX => $homeId,
                static::STAGE_INDEX => $this->tournamentSeasonStage->getId()
            ];
            $indexes[$this->getMultiIndex($multi)] = $startTime;
        }
        
        $awayId = $this->getAwayId();
        if (! empty($awayId)) {
            $indexes[$this->getColumnIndex(static::TEAM_INDEX, $awayId)] = $startTime;
            $multi = [
                static::TEAM_INDEX => $awayId,
                static::STAGE_INDEX => $this->tournamentSeasonStage->getId()
            ];
            $indexes[$this->getMultiIndex($multi)] = $startTime;
        }
        
        $round = $this->getRound();
        if (! empty($round)) {
            $multi = [
                static::STAGE_INDEX => $this->tournamentSeasonStage->getId(),
                static::ROUND_INDEX => $round
            ];
            $indexes[$this->getMultiIndex($multi)] = $startTime;
        }
        
        if ($homeId && $awayId) {
            
            $indexArr = [
                Event::TEAM_HOME_INDEX => $homeId,
                Event::TEAM_AWAY_INDEX => $awayId
            ];
            
            $indexName = 'by_teams:' . $this->getMultiIndex($indexArr);
            $indexes[$indexName] = $startTime;
        }
        
        return $indexes;
    }

    public static function getTimeIndexValue(\DateTime $timestamp, $reverse = false)
    {
        $other = clone $timestamp;
        $other->setTimezone(new \DateTimeZone('UTC'));
        $current = static::getWeekId($other);
        $sign = ($reverse) ? '-' : '+';
        $other->modify($sign . static::TIME_INDEX_STEP);
        $other = static::getWeekId($other);
        return ($reverse) ? $other . '-' . $current : $current . '-' . $other;
    }
    
    public static function getMonday(\DateTime $from) {
        $monday = clone $from;
        $monday->setTimezone(new \DateTimeZone('UTC'));
        $monday->setTime(0 ,0, 0);
        if ($monday->format('N') == 1) {
            // If the date is already a Monday, return it as-is
            return $monday;
        } else {
            // Otherwise, return the date of the nearest Monday in the past
            return $monday->modify('last monday');
        }
    }

    public static function getTimeIndexStart(\DateTime $from)
    {
        return static::getMonday($from);
    }

    public static function getTimeIndexEnd(\DateTime $from)
    {
        $next = clone $from;
        $next->setTimezone(new \DateTimeZone('UTC'));
        $next->modify('+' . static::TIME_INDEX_STEP);
        $firstDay = new \DateTime($next->format('Y') . 'W' . $next->format('W'));
        return $firstDay->modify('+168 hours')->modify('-1 second');
    }

    private function getTimeIndexes()
    {
        $startTime = $this->getStartTime();
        $reverse = static::getTimeIndexValue($startTime, true);
        $forward = static::getTimeIndexValue($startTime);
        $indexes = [
            $this->getColumnIndex(static::TIME_INDEX, $reverse) => $startTime,
            $this->getColumnIndex(static::TIME_INDEX, $forward) => $startTime
        ];
        return $indexes;
    }

    private static function getWeekId(\DateTime $timestamp)
    {
        return static::getMonday($timestamp)->format('Y-m-d');
    }

    public function getLiveUpdates()
    {
        return $this->liveUpdates;
    }

    public function setLiveUpdates($liveUpdates)
    {
        $this->liveUpdates = (boolean) $liveUpdates;
        return $this;
    }

    public function getTeamstatsAvailable()
    {
        return $this->teamstatsAvailable;
    }

    public function setTeamstatsAvailable($teamstatsAvailable)
    {
        $this->teamstatsAvailable = (boolean) $teamstatsAvailable;
        return $this;
    }

    /**
     * @return \Sportal\FootballApi\Model\TeamScore
     */
    public function getHomeScore()
    {
        return $this->homeScore;
    }

    /**
     * @param \Sportal\FootballApi\Model\TeamScore $homeScore
     * @return \Sportal\FootballApi\Model\Event
     */
    public function setHomeScore(\Sportal\FootballApi\Model\TeamScore $homeScore)
    {
        $this->homeScore = $homeScore;
        return $this;
    }

    /**
     * @return \Sportal\FootballApi\Model\TeamScore
     */
    public function getAwayScore()
    {
        return $this->awayScore;
    }

    /**
     * @param \Sportal\FootballApi\Model\TeamScore $homeScore
     * @return \Sportal\FootballApi\Model\Event
     */
    public function setAwayScore(\Sportal\FootballApi\Model\TeamScore $awayScore)
    {
        $this->awayScore = $awayScore;
        return $this;
    }
}

