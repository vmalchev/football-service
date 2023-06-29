<?php
namespace Sportal\FootballApi\Model;

/**
 *
 * @SWG\Definition(definition="TeamPlayer",
 *                 allOf={
 *                      @SWG\Schema(ref="#/definitions/TeamPlayerData"),
 *                      @SWG\Schema(type="object", properties={
 *                      @SWG\Property(property="player",
 *                            description="Information for the Player",
 *                            ref="#/definitions/Player")
 *                       }, required={"player"})
 *      })
 *
 * @SWG\Definition(
 *      definition="PlayerTeam",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/TeamPlayerData"),
 *          @SWG\Schema(type="object", properties={
 *              @SWG\Property(property="team",
 *                            description="Reference to the Team the player participates in",
 *                            ref="#/definitions/PartialTeam")
 *          }, required={"team"})
 *      })
 *
 * @SWG\Definition(definition="TeamPlayerData", required={"active"})
 */
class TeamPlayer extends TeamPerson
{

    /**
     * Shirt number which the player wears for the team
     * @var integer
     * @SWG\Property(property="shirt_number", example=9)
     */
    private $shirtNumber;

    /**
     * Whether the player is on loan with the team
     * @var boolean
     * @SWG\Property()
     */
    private $loan = false;

    private $sortorder;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return array_merge(parent::getPersistanceMap(), [
            'shirt_number' => $this->shirtNumber,
            'loan' => $this->loan,
            'sortorder' => $this->sortorder
        ]);
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        $data['loan'] = $this->loan;

        if ($this->shirtNumber !== null) {
            $data['shirt_number'] = $this->shirtNumber;
        }

        return $data;
    }

    /**
     * @return integer
     */
    public function getShirtNumber()
    {
        return $this->shirtNumber;
    }

    /**
     * @param integer $shirtNumber
     */
    public function setShirtNumber($shirtNumber)
    {
        $this->shirtNumber = (int) $shirtNumber;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getLoan()
    {
        return $this->loan;
    }

    /**
     * @param boolean $loan
     */
    public function setLoan($loan)
    {
        $this->loan = $loan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    /**
     * @param mixed $sortorder
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = (int) $sortorder;
        return $this;
    }
}

