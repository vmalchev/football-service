<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;
use Sportal\FootballApi\Util\NameUtil;

class TeamPerson implements SurrogateKeyInterface, \JsonSerializable, Translateable
{

    /**
     * Unique identifier of the resource
     * @var integer
     */
    private $id;

    /**
     * @var \Sportal\FootballApi\Model\Person
     */
    private $person;

    /**
     * @var \Sportal\FootballApi\Model\PartialTeam
     */
    private $team;

    /**
     * Whether the person is currently part of the Team
     * @var boolean
     * @SWG\Property()
     */
    private $active;

    /**
     * Date when the Person joined the team
     * @var \DateTime
     * @SWG\Property(property="start_date", type="string", format="date", example="2015-06-20")
     */
    private $startDate;

    /**
     * Date when the Person left the team (if any)
     * @var \DateTime
     * @SWG\Property(property="end_date", type="string", format="date", example="2016-06-21")
     */
    private $endDate;

    private $showTeam = false;

    /**
     * Set startDate
     *
     * @param string $startDate
     *
     * @return TeamPerson
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        
        return $this;
    }

    /**
     * Get startDate
     *
     
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param string $endDate
     *
     * @return TeamPerson
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
        
        return $this;
    }

    /**
     * Get endDate
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return TeamPerson
     */
    public function setActive($active)
    {
        $this->active = $active;
        
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
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
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\SurrogateKeyInterface::setId()
     * @return TeamPerson
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set person
     *
     * @param \Sportal\FootballApi\Model\Person $person
     *
     * @return TeamPerson
     */
    public function setPerson(\Sportal\FootballApi\Model\Person $person)
    {
        $this->person = $person;
        
        return $this;
    }

    /**
     * Get person
     *
     * @return \Sportal\FootballApi\Model\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set team
     *
     * @param \Sportal\FootballApi\Model\PartialTeam $team
     *
     * @return TeamPerson
     */
    public function setTeam(\Sportal\FootballApi\Model\PartialTeam $team)
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
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        $shortName = NameUtil::camel2underscore(NameUtil::shortClassName(get_class($this->person)));
        return [
            $shortName . '_id' => $this->person->getId(),
            'team_id' => $this->team->getId(),
            'start_date' => ($this->startDate !== null) ? $this->startDate->format('Y-m-d') : null,
            'end_date' => ($this->endDate !== null) ? $this->endDate->format('Y-m-d') : null,
            'active' => $this->active
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
        $shortName = NameUtil::camel2underscore(NameUtil::shortClassName(get_class($this->person)));
        $data = [];
        if ($this->showTeam) {
            $data['team'] = $this->team;
        } else {
            $data[$shortName] = $this->person;
        }
        
        $data['active'] = $this->active;
        
        if ($this->startDate !== null) {
            $data['start_date'] = $this->startDate->format('Y-m-d');
        }
        
        if ($this->endDate !== null) {
            $data['end_date'] = $this->endDate->format('Y-m-d');
        }
        
        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        if ($this->showTeam) {
            return $this->team->getMlContentModels();
        } else {
            return $this->person->getMlContentModels();
        }
    }

    public function getShowTeam()
    {
        return $this->showTeam;
    }

    public function setShowTeam($showTeam)
    {
        $this->showTeam = $showTeam;
        return $this;
    }
}

