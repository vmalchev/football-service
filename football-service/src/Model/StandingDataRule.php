<?php
namespace Sportal\FootballApi\Model;

class StandingDataRule implements SurrogateKeyInterface, Comparable
{

    /**
     *
     * @var integer
     */
    private $id;

    /**
     *
     * @var integer
     */
    private $standingId;

    /**
     *
     * @var \Sportal\FootballApi\Model\StandingRule
     */
    private $standingRule;

    /**
     *
     * @var integer
     */
    private $rank;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'standing_id' => $this->standingId,
            'standing_rule_id' => $this->standingRule->getId(),
            'rank' => $this->rank
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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getStandingId()
    {
        return $this->standingId;
    }

    public function setStandingId($standingId)
    {
        $this->standingId = $standingId;
        return $this;
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\StandingRule
     */
    public function getStandingRule()
    {
        return $this->standingRule;
    }

    /**
     *
     * @param \Sportal\FootballApi\Model\StandingRule $standingRule
     * @return \Sportal\FootballApi\Model\StandingDataRule
     */
    public function setStandingRule(\Sportal\FootballApi\Model\StandingRule $standingRule)
    {
        $this->standingRule = $standingRule;
        return $this;
    }

    public function getRank()
    {
        return $this->rank;
    }

    public function setRank($rank)
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Comparable::equals()
     */
    public function equals($other)
    {
        return $this->getStandingId() == $other->getStandingId() &&
             $this->standingRule->getId() == $other->getStandingRule()->getId() && $this->getRank() == $other->getRank();
    }
}