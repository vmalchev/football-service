<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="TeamStatistics",
 *                 properties={
 *                      @SWG\Property(property="pass", type="integer", description="Number of keys passes completed"),
 *                      @SWG\Property(property="possession", type="integer", description="Percentage of time the team possess the ball"),
 *                      @SWG\Property(property="goals", type="integer", description="Number of goals scored"),
 *                      @SWG\Property(property="corners", type="integer", description="Number of corners"),
 *                      @SWG\Property(property="crosses", type="integer", description="Number of crosses"),
 *                      @SWG\Property(property="offside", type="integer", description="Number of offsides"),
 *                      @SWG\Property(property="shots_on", type="integer", description="Shots on that have hit target"),
 *                      @SWG\Property(property="shots_blocked", type="integer", description="Number of shots that have been blocked by an opposition player"),
 *                      @SWG\Property(property="shots_off", type="integer", description="Shots outside the target"),
 *                      @SWG\Property(property="throw_in", type="integer", description="Number of throw ins taken"),
 *                      @SWG\Property(property="goal_kicks", type="integer", description="Number of goal kicks taken"),
 *                      @SWG\Property(property="treatments", type="integer", description="Number of treatments to injuries"),
 *                      @SWG\Property(property="yellow_cards", type="integer", description="Total numer of yellow cards"),
 *                      @SWG\Property(property="substitutions", type="integer", description="Number of substitutions"),
 *                      @SWG\Property(property="counter_attacks", type="integer", description="Number of counter attacks"),
 *                      @SWG\Property(property="fouls_committed", type="integer", description="Total number of fouls committed by the Team")
 *                 },
 *                 required={"goals", "possession", "corners", "shots_on", "shots_off", "shots_blocked", "offside", "fouls_committed"})
 * @SWG\Definition(required={"team", "home_team", "statistics"})
 */
class EventTeamStats implements SurrogateKeyInterface, \JsonSerializable
{
    
    use UpdateTimestamp;

    /**
     * Unique identifier
     * @var integer
     */
    private $id;

    /**
     * The Team whose stats are represented in the object
     * @var \Sportal\FootballApi\Model\PartialTeam
     * @SWG\Property()
     */
    private $team;

    /**
     * Whether the stats are for the home or the away team
     * @var boolean
     * @SWG\Property(property="home_team")
     */
    private $homeTeam;

    /**
     * Object containing the team statistics
     * @SWG\Property(type="object", ref="#/definitions/TeamStatistics")
     */
    private $statistics;

    /**
     * Identifier of the event the stat is related to
     * @var integer
     * @SWG\Property(property="event_id")
     */
    private $eventId;

    /**
     * Human readable name of the team for which statistics are shown
     * @var string
     */
    private $teamName;

    /**
     * Set teamName
     *
     * @param string $teamName
     *
     * @return EventTeamStats
     */
    public function setTeamName($teamName)
    {
        $this->teamName = $teamName;
        
        return $this;
    }

    /**
     * Get teamName
     *
     * @return string
     */
    public function getTeamName()
    {
        return $this->teamName;
    }

    /**
     * Set statistics
     *
     * @param array $statistics
     *
     * @return EventTeamStats
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
        
        return $this;
    }

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set team
     *
     * @param \Sportal\FootballApi\Model\PartialTeam $team
     *
     * @return EventTeamStats
     */
    public function setTeam(\Sportal\FootballApi\Model\PartialTeam $team = null)
    {
        $this->team = $team;
        
        return $this;
    }

    /**
     * Get team
     *
     * @return \Sportal\FootballApi\Model\PartialTeam
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\SurrogateKeyInterface::setId()
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param integer $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = (int) $eventId;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * @param boolean $homeTeam
     */
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = (boolean) $homeTeam;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'event_id' => $this->eventId,
            'team_id' => $this->team !== null ? $this->team->getId() : null,
            'team_name' => $this->team !== null ? $this->team->getName() : $this->teamName,
            'home_team' => $this->homeTeam,
            'statistics' => json_encode($this->statistics)
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'id' => $this->id
        ];
    }

    public function jsonSerialize()
    {
        $data = [
            'event_id' => $this->eventId,
            'statistics' => $this->statistics,
            'home_team' => $this->homeTeam
        ];
        
        if ($this->team !== null) {
            $data['team'] = $this->team;
        } else {
            $data['team'] = [
                'name' => $this->teamName
            ];
        }
        
        return array_merge($data, $this->jsonUpdatedAt());
    }
}

