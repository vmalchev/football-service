<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;
use Sportal\FootballApi\Database\SurrogateKeyInterface as SurrogateInterface;
use Sportal\FootballApi\Cache\Index\IndexableInterface;
use Sportal\FootballApi\Cache\Index\GeneratesIndexName;

/**
 * EventPlayer
 * @SWG\Definition(required={"id", "player", "event_player_type", "home_team"})
 */
class EventPlayer implements SurrogateInterface, \JsonSerializable, Translateable, Comparable, IndexableInterface
{
    
    use UpdateTimestamp, GeneratesIndexName;

    const EVENT_INDEX = 'event_id';

    /**
     * Unique identifier
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Object describing the player in the lineup
     * @var \Sportal\FootballApi\Model\PartialPerson
     * @SWG\Property()
     */
    private $player;

    /**
     * Object describing the type of lineup
     * @var \Sportal\FootballApi\Model\EventPlayerType
     * @SWG\Property(property="event_player_type")
     */
    private $eventPlayerType;

    /**
     * Whether the player is from the home or the away team
     * @var boolean
     * @SWG\Property(property="home_team")
     */
    private $homeTeam;

    /**
     * Number indicating the player's vertical position on the field. 1 = Goalkeeper, 2-5 = Defender, 6-9 = Midfielder, 10-11 = Forward, where 5 is an offensive defender, 9 is an offensive midfielder, etc.
     * @var integer
     * @SWG\Property(property="position_x", example=10)
     */
    private $positionX;

    /**
     * Number indicating the player's horizontal position on the field. Ranges from 1-9, where 1 = furthest right, 9 = furthest left.
     * @var integer
     * @SWG\Property(property="position_y", example=5)
     */
    private $positionY;

    /**
     *
     * Number 1-11 indicating the player's position in the lineup. 1 = goalkeeper, 2 = defender furthest right.
     * @var integer
     * @SWG\Property(property="position_number", example=11)
     */
    private $positionNumber;

    /**
     * The shirt number the player is wearing
     * @var integer
     * @SWG\Property(property="shirt_number", example=9)
     */
    private $shirtNumber;

    /**
     * Minute when the player was substituted in. Available if event_player_type.category = sub
     * @var integer
     */
    private $subIn;

    /**
     * Object describing when the player substituted out and who came on.
     * @var \Sportal\FootballApi\Model\EventPlayerSubOn
     */
    private $subOn;

    /**
     * @var integer
     */
    private $eventId;

    /**
     * @var string
     */
    private $playerName;

    /**
     * Set playerName
     *
     * @param string $playerName
     *
     * @return EventPlayer
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
     * Set positionX
     *
     * @param boolean $positionX
     *
     * @return EventPlayer
     */
    public function setPositionX($positionX)
    {
        $this->positionX = (int) $positionX;
        
        return $this;
    }

    /**
     * Get positionX
     *
     * @return boolean
     */
    public function getPositionX()
    {
        return $this->positionX;
    }

    /**
     * Set positionY
     *
     * @param boolean $positionY
     *
     * @return EventPlayer
     */
    public function setPositionY($positionY)
    {
        $this->positionY = (int) $positionY;
        
        return $this;
    }

    /**
     * Get positionY
     *
     * @return boolean
     */
    public function getPositionY()
    {
        return $this->positionY;
    }

    /**
     * Set shirtNumber
     *
     * @param integer $shirtNumber
     *
     * @return EventPlayer
     */
    public function setShirtNumber($shirtNumber)
    {
        $this->shirtNumber = (int) $shirtNumber;
        
        return $this;
    }

    /**
     * Get shirtNumber
     *
     * @return integer
     */
    public function getShirtNumber()
    {
        return $this->shirtNumber;
    }

    /**
     * Set subIn
     *
     * @param integer $subIn
     *
     * @return EventPlayer
     */
    public function setSubIn($subIn)
    {
        $this->subIn = (int) $subIn;
        
        return $this;
    }

    /**
     * Get subIn
     *
     * @return integer
     */
    public function getSubIn()
    {
        return $this->subIn;
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
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param integer $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
        return $this;
    }

    /**
     * Set eventPlayerType
     *
     * @param \Sportal\FootballApi\Model\\EventPlayerType $eventPlayerType
     *
     * @return EventPlayer
     */
    public function setEventPlayerType(\Sportal\FootballApi\Model\EventPlayerType $eventPlayerType)
    {
        $this->eventPlayerType = $eventPlayerType;
        
        return $this;
    }

    /**
     * Get eventPlayerType
     *
     * @return \Sportal\FootballApi\Model\EventPlayerType
     */
    public function getEventPlayerType()
    {
        return $this->eventPlayerType;
    }

    /**
     * Set player
     *
     * @param \Sportal\FootballApi\Model\PartialPerson $player
     *
     * @return EventPlayer
     */
    public function setPlayer(\Sportal\FootballApi\Model\PartialPerson $player = null)
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
     * Get the Player Id
     * @return NULL|integer
     */
    public function getPlayerId()
    {
        return ($this->player !== null) ? $this->player->getId() : null;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'event_id' => $this->eventId,
            'player_id' => $this->getPlayerId(),
            'player_name' => $this->playerName,
            'event_player_type_id' => $this->eventPlayerType->getId(),
            'home_team' => $this->homeTeam,
            'position_x' => $this->positionX,
            'position_y' => $this->positionY,
            'shirt_number' => $this->shirtNumber
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
            'id' => $this->id,
            'event_player_type' => $this->eventPlayerType,
            'home_team' => $this->homeTeam
        ];
        
        if ($this->player !== null) {
            $data['player'] = $this->player;
        } else {
            $data['player'] = [
                'name' => $this->playerName
            ];
        }
        
        if ($this->positionNumber !== null) {
            $data['position_number'] = $this->positionNumber;
        }
        
        if ($this->positionX !== null) {
            $data['position_x'] = $this->positionX;
        }
        
        if ($this->positionX !== 1 && $this->positionY !== null) {
            $data['position_y'] = $this->positionY;
        }
        
        if ($this->shirtNumber !== null) {
            $data['shirt_number'] = $this->shirtNumber;
        }
        
        return $data;
    }

    /**
     * @return \Sportal\FootballApi\Model\EventPlayerSubOn
     */
    public function getSubOn()
    {
        return $this->subOn;
    }

    /**
     * @param \Sportal\FootballApi\Model\EventPlayerSubOn $subOn
     * @return EventPlayer
     */
    public function setSubOn(\Sportal\FootballApi\Model\EventPlayerSubOn $subOn)
    {
        $this->subOn = $subOn;
        return $this;
    }

    public function equals($other)
    {
        return ($this->getEventId() == $other->getEventId() && $this->getPlayerId() == $other->getPlayerId() &&
             $this->getPlayerName() == $other->getPlayerName());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $arr = [
            $this->eventPlayerType
        ];
        if ($this->player !== null) {
            foreach ($this->player->getMlContentModels() as $model) {
                $arr[] = $model;
            }
        }
        if ($this->subOn !== null && $this->subOn->getPlayerId() !== null) {
            $arr[] = $this->subOn;
        }
        return $arr;
    }

    public function getPositionNumber()
    {
        return $this->positionNumber;
    }

    public function setPositionNumber($positionNumber)
    {
        $this->positionNumber = $positionNumber;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\Index\IndexableInterface::getSortedIndecies()
     */
    public function getSortedIndecies()
    {
        $score = (int) ! $this->homeTeam;
        $score += ((int) $this->eventPlayerType->getSortorder()) / 100;
        $score += ((int) $this->positionX) / 10000;
        $score += ((int) $this->positionY) / 100000;
        
        return [
            $this->getColumnIndex(static::EVENT_INDEX, $this->eventId) => $score
        ];
    }

    public function getUnique()
    {
        return $this->eventId . "-" . $this->playerName . "-" . $this->getPlayerId();
    }
}

