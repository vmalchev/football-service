<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(required={"id", "start_time", "home_team", "away_team", "event_status", "goal_home", "goal_away"})
 */
class PartialEvent implements \JsonSerializable, Translateable
{

    const OUTCOME_HOME = '1';

    const OUTCOME_DRAW = 'x';

    const OUTCOME_AWAY = '2';

    const OUTCOME_MAP = [
        1 => '1',
        0 => 'x',
        - 1 => '2'
    ];

    /**
     * Unique identifier
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Description of the event's current status
     * @var \Sportal\FootballApi\Model\EventStatus
     * @SWG\Property(property="event_status")
     */
    private $eventStatus;

    /**
     * Timestamp when the event is scheduled to start
     * @var \DateTime
     * @SWG\Property(property="start_time")
     */
    private $startTime;

    /**
     * Round type of the TournamentSeasonStage in which the event is
     * @var \Sportal\FootballApi\Model\RoundType
     */
    private $roundType;

    /**
     * Goals scored by the home team
     * @var integer
     * @SWG\Property(property="goal_home", example=2)
     */
    private $goalHome;

    /**
     * Goals scored by the away team
     * @var integer
     * @SWG\Property(property="goal_away", example=1)
     */
    private $goalAway;

    /**
     * Penalties scored by the home team. Available only if the game goes to penalty shootouts
     * @SWG\Property(property="penalty_home", example=5)
     * @var integer
     */
    private $penaltyHome;

    /**
     * Penalties scored by the away team. Available only if the game goes to penalty shootouts
     * @var integer
     * @SWG\Property(property="penalty_away", example=4)
     */
    private $penaltyAway;

    /**
     * Goals scored by home team over 1 or more legs. Available only if the tie has mutliple legs
     * @var integer
     * @SWG\Property(property="agg_home", example=4)
     */
    private $aggHome;

    /**
     * Goals scored by home team over 1 or more legs. Available only if the tie has mutliple legs
     * @var integer
     * @SWG\Property(property="agg_away", example=2)
     */
    private $aggAway;

    /**
     * Home Team
     * @var \Sportal\FootballApi\Model\PartialTeam
     * @SWG\Property(property="home_team")
     */
    private $homeTeam;

    /**
     * Away Team
     * @var \Sportal\FootballApi\Model\PartialTeam
     * @SWG\Property(property="away_team")
     */
    private $awayTeam;

    /**
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param integer $id
     * @return \Sportal\FootballApi\Model\PartialEvent
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Event
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
        
        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set round type
     *
     * @param string \Sportal\FootballApi\Model\RoundType
     *
     * @return PartialEvent
     */
    public function setRoundType($roundType)
    {
        $this->roundType = $roundType;

        return $this;
    }

    /**
     * Get round type
     *
     * @return \Sportal\FootballApi\Model\RoundType
     */
    public function getRoundType()
    {
        return $this->roundType;
    }

    public function getRound(): ?string
    {
        return $this->roundType !== null ? $this->roundType->getName() : null;
    }

    /**
     * Set GoalHome
     *
     * @param integer $goalHome
     *
     * @return PartialEvent
     */
    public function setGoalHome($goalHome)
    {
        $this->setInt('goalHome', $goalHome);
        return $this;
    }

    /**
     * Get GoalHome
     *
     * @return integer
     */
    public function getGoalHome()
    {
        return $this->goalHome;
    }

    /**
     * Set goalAway
     *
     * @param integer $goalAway
     *
     * @return PartialEvent
     */
    public function setGoalAway($goalAway)
    {
        $this->setInt('goalAway', $goalAway);
        return $this;
    }

    /**
     * Get goalAway
     *
     * @return integer
     */
    public function getGoalAway()
    {
        return $this->goalAway;
    }

    /**
     * Set penaltyHome
     *
     * @param boolean $penaltyHome
     *
     * @return PartialEvent
     */
    public function setPenaltyHome($penaltyHome)
    {
        $this->setInt('penaltyHome', $penaltyHome);
        return $this;
    }

    /**
     * Get penaltyHome
     *
     * @return boolean
     */
    public function getPenaltyHome()
    {
        return $this->penaltyHome;
    }

    /**
     * Set penaltyAway
     *
     * @param boolean $penaltyAway
     *
     * @return PartialEvent
     */
    public function setPenaltyAway($penaltyAway)
    {
        $this->setInt('penaltyAway', $penaltyAway);
        return $this;
    }

    /**
     * Get penaltyAway
     *
     * @return boolean
     */
    public function getPenaltyAway()
    {
        return $this->penaltyAway;
    }

    /**
     * Set aggHome
     *
     * @param boolean $aggHome
     *
     * @return PartialEvent
     */
    public function setAggHome($aggHome)
    {
        $this->setInt('aggHome', $aggHome);
        return $this;
    }

    /**
     * Get aggHome
     *
     * @return boolean
     */
    public function getAggHome()
    {
        return $this->aggHome;
    }

    /**
     * Set aggAway
     *
     * @param boolean $aggAway
     *
     * @return PartialEvent
     */
    public function setAggAway($aggAway)
    {
        $this->setInt('aggAway', $aggAway);
        return $this;
    }

    /**
     * Get aggAway
     *
     * @return boolean
     */
    public function getAggAway()
    {
        return $this->aggAway;
    }

    /**
     * Set eventStatus
     *
     * @param \Sportal\FootballApi\Model\EventStatus $eventStatus
     *
     * @return Event
     */
    public function setEventStatus(\Sportal\FootballApi\Model\EventStatus $eventStatus = null)
    {
        $this->eventStatus = $eventStatus;
        
        return $this;
    }

    /**
     * Get eventStatus
     *
     * @return \Sportal\FootballApi\Model\EventStatus
     */
    public function getEventStatus()
    {
        return $this->eventStatus;
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\PartialTeam
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     *
     * @param \Sportal\FootballApi\Model\PartialTeam $homeTeam
     * @return \Sportal\FootballApi\Model\Event
     */
    public function setHomeTeam(\Sportal\FootballApi\Model\PartialTeam $homeTeam)
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\PartialTeam
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     *
     * @param \Sportal\FootballApi\Model\PartialTeam $awayTeam
     * @return \Sportal\FootballApi\Model\Event
     */
    public function setAwayTeam(\Sportal\FootballApi\Model\PartialTeam $awayTeam)
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    public function getOutcome()
    {
        if ($this->goalHome !== null && $this->goalAway !== null) {
            $sign = static::sign($this->goalHome - $this->goalAway);
            if ($sign === 0 && $this->penaltyHome !== null && $this->penaltyAway !== null) {
                $sign = static::sign($this->penaltyHome - $this->penaltyAway);
            }
            return static::OUTCOME_MAP[$sign];
        }
        return null;
    }

    public function isHomeWin()
    {
        return $this->getOutcome() === static::OUTCOME_HOME;
    }

    public function isAwayWin()
    {
        return $this->getOutcome() === static::OUTCOME_AWAY;
    }

    public function isDraw()
    {
        return $this->getOutcome() === static::OUTCOME_DRAW;
    }

    public function getHomeId()
    {
        return $this->homeTeam !== null ? $this->homeTeam->getId() : null;
    }

    public function getAwayId()
    {
        return $this->awayTeam !== null ? $this->awayTeam->getId() : null;
    }

    public static function sign($num)
    {
        return $num < 0 ? - 1 : ($num > 0 ? 1 : 0);
    }

    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'event_status' => $this->eventStatus,
            'start_time' => $this->startTime->format(\DateTime::ATOM),
            'home_team' => $this->homeTeam,
            'away_team' => $this->awayTeam,
            'round' => null
        ];

        if ($this->roundType !== null) {
            $data['round'] = $this->roundType->jsonSerialize()['name'];
        }
        
        if ($this->goalHome !== null) {
            $data['goal_home'] = $this->goalHome;
        }
        
        if ($this->goalAway !== null) {
            $data['goal_away'] = $this->goalAway;
        }
        
        if ($this->penaltyHome !== null) {
            $data['penalty_home'] = $this->penaltyHome;
        }
        
        if ($this->penaltyAway !== null) {
            $data['penalty_away'] = $this->penaltyAway;
        }
        
        if ($this->aggHome !== null) {
            $data['agg_home'] = $this->aggHome;
        }
        
        if ($this->aggAway !== null) {
            $data['agg_away'] = $this->aggAway;
        }
        
        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $objects = [
            $this->eventStatus
        ];

        if ($this->roundType !== null) {
            foreach ($this->roundType->getMlContentModels() as $model) {
                $objects[] = $model;
            }
        }
        
        if ($this->homeTeam !== null) {
            foreach ($this->homeTeam->getMlContentModels() as $model) {
                $objects[] = $model;
            }
        }
        
        if ($this->awayTeam !== null) {
            foreach ($this->awayTeam->getMlContentModels() as $model) {
                $objects[] = $model;
            }
        }
        
        return $objects;
    }

    public function clonePartial()
    {
        $partial = new PartialEvent();
        $partial->setId($this->id)
            ->setEventStatus($this->eventStatus)
            ->setStartTime($this->startTime)
            ->setHomeTeam($this->homeTeam)
            ->setAwayTeam($this->awayTeam)
            ->setRoundType($this->roundType)
            ->setGoalHome($this->goalHome)
            ->setGoalAway($this->goalAway)
            ->setPenaltyHome($this->penaltyHome)
            ->setPenaltyAway($this->penaltyAway)
            ->setAggHome($this->aggHome)
            ->setAggAway($this->aggAway);
        return $partial;
    }

    private function setInt($prop, $value)
    {
        if ($value === null) {
            $this->{$prop} = null;
        } else {
            $this->{$prop} = (int) $value;
        }
    }
}
