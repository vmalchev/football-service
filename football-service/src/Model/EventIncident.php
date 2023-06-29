<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;
use Sportal\FootballApi\Cache\Index\IndexableInterface;
use Sportal\FootballApi\Cache\Index\GeneratesIndexName;

/**
 * A football incident during an event, such as goal socred, red card, substitution, etc.
 * @SWG\Definition(required={"id", "event_id", "type", "home_team", "minute"})
 */
class EventIncident implements SurrogateKeyInterface, \JsonSerializable, Translateable, IndexableInterface,
    \Sportal\FootballApi\Database\SurrogateKeyInterface
{
    use GeneratesIndexName, UpdateTimestamp;

    const INDEX_EVENT = 'event_id';

    const INDEX_UPDATED = 'updated_at';

    /**
     * Unique identifier
     * @var integer
     * @SWG\Property(example=1)
     */
    private $id;

    /**
     * Identifier of the event during which the incident occurred
     * @var integer
     * @SWG\Property(example=1, property="event_id")
     */
    private $eventId;

    /**
     * The type of incident
     * @var string
     * @SWG\Property(enum={"penalty_shootout_scored", "yellow_card_red", "penalty_shootout_missed", "substitution", "goal", "red_card", "penalty_miss", "yellow_card", "penalty_goal", "own_goal"})
     */
    private $type;

    /**
     * Whether the incident is related to the home or away team. true if home team
     * @var boolean
     * @SWG\Property(property="home_team")
     */
    private $homeTeam;

    /**
     * The minute when the incident occured
     * @var integer
     * @SWG\Property(example=68)
     */
    private $minute;

    /**
     * Id of the team for which the incident is related
     * @var integer
     * @SWG\Property(property="team_id")
     */
    private $teamId;

    /**
     * Updated home team score if the incident changes the scoreline (penalty, goal, own_goal, etc)
     * @var integer
     * @SWG\Property(example=2, property="goal_home")
     */
    private $goalHome;

    /**
     * Updated away team score  if the incident changes the scoreline (penalty, goal, own_goal, etc)
     * @var integer
     * @SWG\Property(example=1, property="goal_away")
     */
    private $goalAway;

    /**
     * Player who is the main actor in the incident: Goal Scorer, player who received yellow card. If type == 'substitution' this is the player coming off
     * @var \Sportal\FootballApi\Model\PartialPerson
     * @SWG\Property()
     */
    private $player;

    /**
     * Player who is of secondary importance to the incident: Player who assisted a goal. If type == 'substitution' this is the player coming on
     * @var \Sportal\FootballApi\Model\PartialPerson
     * @SWG\Property(property="rel_player")
     */
    private $relPlayer;

    /**
     * Whether the incident has been deleted or not - a disallowed goal for example
     * @var boolean
     * @SWG\Property()
     */
    private $deleted = false;

    /**
     * If there are multiple incidents with the same minute, this indicates how they are sorted
     * @var integer
     * @SWG\Property()
     */
    private $sortorder;

    /**
     * @var string
     */
    private $playerName;

    /**
     * @var string
     */
    private $relPlayerName;

    /**
     * Set type
     *
     * @param string $type
     *
     * @return EventIncident
     */
    public function setType($type)
    {
        $this->type = $type;
        
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return EventIncident
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;
        
        return $this;
    }

    /**
     * Get playerName
     *
     * @return string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }

    /**
     * Set relPlayerName
     *
     * @param string $relPlayerName
     *
     * @return EventIncident
     */
    public function setRelPlayerName($relPlayerName)
    {
        $this->relPlayerName = $relPlayerName;
        
        return $this;
    }

    /**
     * Get relPlayerName
     *
     * @return string
     */
    public function getRelPlayerName()
    {
        return $this->relPlayerName;
    }

    /**
     * Set goalHome
     *
     * @param integer $goalHome
     *
     * @return EventIncident
     */
    public function setGoalHome($goalHome)
    {
        $this->goalHome = (int) $goalHome;
        
        return $this;
    }

    /**
     * Get goalHome
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
     * @return EventIncident
     */
    public function setGoalAway($goalAway)
    {
        $this->goalAway = (int) $goalAway;
        
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
     * Set sortorder
     *
     * @param integer $sortorder
     *
     * @return EventIncident
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = (int) $sortorder;
        
        return $this;
    }

    /**
     * Get sortorder
     *
     * @return integer
     */
    public function getSortorder()
    {
        return $this->sortorder;
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
     * Set player
     *
     * @param \Sportal\FootballApi\Model\PartialPerson $player
     *
     * @return EventIncident
     */
    public function setPlayer(\Sportal\FootballApi\Model\PartialPerson $player)
    {
        $this->player = $player;
        
        return $this;
    }

    /**
     * Get player
     *
     * @return \Sportal\FootballApi\Model\PartialPerson
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set relPlayer
     *
     * @param \Sportal\FootballApi\Model\PartialPerson $relPlayer
     *
     * @return EventIncident
     */
    public function setRelPlayer(\Sportal\FootballApi\Model\PartialPerson $relPlayer)
    {
        $this->relPlayer = $relPlayer;
        
        return $this;
    }

    /**
     * Get relPlayer
     *
     * @return \Sportal\FootballApi\Model\PartialPerson
     */
    public function getRelPlayer()
    {
        return $this->relPlayer;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return the integer
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
     * @return the boolean
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
        $this->homeTeam = (bool) $homeTeam;
        return $this;
    }

    /**
     * @return the integer
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

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'event_id' => $this->eventId,
            'type' => $this->type,
            'home_team' => $this->homeTeam,
            'minute' => $this->minute,
            'player_id' => ($this->player !== null) ? $this->player->getId() : null,
            'player_name' => $this->playerName,
            'rel_player_id' => ($this->relPlayer !== null) ? $this->relPlayer->getId() : null,
            'rel_player_name' => $this->relPlayerName,
            'goal_home' => $this->goalHome,
            'goal_away' => $this->goalAway,
            'sortorder' => $this->sortorder,
            'deleted' => $this->deleted
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
        $array = [
            'id' => $this->id,
            'type' => $this->type,
            'event_id' => $this->eventId,
            'home_team' => $this->homeTeam,
            'minute' => $this->minute
        ];
        
        if (! empty($this->teamId)) {
            $array['team_id'] = $this->teamId;
        }
        
        if ($this->player !== null) {
            $array['player'] = $this->player;
        } elseif (! empty($this->playerName)) {
            $array['player'] = [
                'name' => $this->playerName
            ];
        }
        
        if ($this->goalHome !== null) {
            $array['goal_home'] = $this->goalHome;
        }
        
        if ($this->goalAway !== null) {
            $array['goal_away'] = $this->goalAway;
        }
        
        if ($this->relPlayer !== null) {
            $array['rel_player'] = $this->relPlayer;
        } elseif (! empty($this->relPlayerName)) {
            $array['rel_player'] = [
                'name' => $this->relPlayerName
            ];
        }
        
        if (! empty($this->sortorder)) {
            $array['sortorder'] = $this->sortorder;
        }
        
        if (! empty($this->deleted)) {
            $array['deleted'] = $this->deleted;
        }
        
        return array_merge($array, $this->jsonUpdatedAt());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $arr = [];
        if ($this->player !== null) {
            $arr[] = $this->player;
        }
        if ($this->relPlayer !== null) {
            $arr[] = $this->relPlayer;
        }
        return $arr;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($deleted)
    {
        $this->deleted = (boolean) $deleted;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\Index\IndexableInterface::getSortedIndecies()
     */
    public function getSortedIndecies()
    {
        $indexes = $this->getUpdatedIndex();
        if (! $this->deleted) {
            $indexes[$this->getColumnIndex(static::INDEX_EVENT, $this->eventId)] = $this->minute +
                 ($this->sortorder / 100);
        }
        return $indexes;
    }

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function setTeamId($teamId)
    {
        $this->teamId = (int) $teamId;
        return $this;
    }
}

