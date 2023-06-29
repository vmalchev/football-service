<?php
namespace Sportal\FootballApi\Model;

class EventCoach implements ModelInterface, \JsonSerializable, Translateable
{

    /**
     *
     * @var string
     */
    private $coachName;

    /**
     *
     * @var \Sportal\FootballApi\Model\Coach
     */
    private $coach;

    /**
     *
     * @var boolean
     */
    private $homeTeam;

    /**
     *
     * @var integer
     */
    private $eventId;

    public function getPrimaryKeyMap()
    {
        return [
            'event_id' => $this->eventId,
            'home_team' => (int) $this->homeTeam
        ];
    }

    public function getPersistanceMap()
    {
        return [
            'coach_name' => $this->coachName,
            'coach_id' => $this->coach !== null ? $this->coach->getId() : null,
            'home_team' => $this->homeTeam,
            'event_id' => $this->eventId
        ];
    }

    /**
     * @return \Sportal\FootballApi\Model\Coach
     */
    public function getCoach()
    {
        return $this->coach;
    }

    /**
     * @param \Sportal\FootballApi\Model\Coach $coach
     */
    public function setCoach(\Sportal\FootballApi\Model\PartialPerson $coach)
    {
        $this->coach = $coach;
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
        $this->homeTeam = (bool) $homeTeam;
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

    public function getCoachName()
    {
        return $this->coachName;
    }

    public function setCoachName($coachName)
    {
        $this->coachName = $coachName;
        return $this;
    }

    public function jsonSerialize()
    {
        if ($this->coach !== null) {
            return $this->coach;
        }

        else {
            return [
                'name' => $this->coachName
            ];
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        if ($this->coach !== null) {
            return [
                $this->coach
            ];
        }
        return [];
    }
}