<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(allOf={@SWG\Schema(ref="#/definitions/PlayerStatsExtended")}, required={"player"})
 */
class BasicPlayerStats implements \JsonSerializable, Translateable
{
    
    /**
     * Player information
     * @var Player
     * @SWG\Property()
     */
    private $player;

    /**
     * Player's shirt number during the season
     * @var integer
     * @SWG\Property(property="shirt_number")
     */
    private $shirtNumber;

    /**
     *
     * @var array
     */
    private $statistics;

    public function __construct(PlayerStatistics $stats = null)
    {
        if ($stats !== null) {
            $this->setPlayer($stats->getPlayer())
                ->setStatistics($stats->getStatistics())
                ->setShirtNumber($stats->getShirtNumber());
        }
    }

    /**
     * @return PartialPerson
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param PartialPerson $player
     */
    public function setPlayer(PartialPerson $player)
    {
        $this->player = $player;
        return $this;
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
        $this->shirtNumber = $shirtNumber;
        return $this;
    }

    /**
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * @param array $statistics
     */
    public function setStatistics(array $statistics)
    {
        $this->statistics = $statistics;
        return $this;
    }

    public function jsonSerialize()
    {
        $data = array_merge([
            'player' => $this->player
        ], $this->statistics);
        
        if (isset($this->shirtNumber)) {
            $data['shirt_number'] = $this->shirtNumber;
        }
        
        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        return $this->player->getMlContentModels();
    }
}
