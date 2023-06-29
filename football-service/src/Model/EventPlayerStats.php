<?php
namespace Sportal\FootballApi\Model;

/**
 * EventPlayerStats
 */
class EventPlayerStats implements ModelInterface, \JsonSerializable
{

    /**
     * @var array
     */
    private $statistics;

    /**
     * @var integer
     */
    private $eventPlayerId;

    /**
     * Set statistics
     *
     * @param array $statistics
     *
     * @return EventPlayerStats
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
     * Set eventPlayer
     *
     * @param integer $eventPlayer
     *
     * @return EventPlayerStats
     */
    public function setEventPlayerId($eventPlayerId)
    {
        $this->eventPlayerId = (int) $eventPlayerId;
        
        return $this;
    }

    /**
     * Get eventPlayerId
     *
     * @return integer
     */
    public function getEventPlayerId()
    {
        return $this->eventPlayerId;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'event_player_id' => $this->eventPlayerId,
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
            'event_player_id' => $this->eventPlayerId
        ];
    }

    public function jsonSerialize()
    {
        return $this->statistics;
    }
}

