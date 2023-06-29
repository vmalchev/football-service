<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 *
 * @SWG\Definition(required={"player_name", "elapsed"})
 */
class EventPlayerSubOn implements \JsonSerializable, MlContainerInterface
{
    use ContainsMlContent;

    public function __construct(array $data)
    {
        if (isset($data['player_id'])) {
            $this->setPlayerId($data['player_id']);
        }
        $this->setPlayerName($data['player_name']);
        $this->setElapsed($data['elapsed']);
    }

    /**
     * Id of the player that comes on
     * @var integer
     * @SWG\Property(property="player_id", example=1)
     */
    private $playerId;

    /**
     * Name of the player that comes on
     * @var string
     * @SWG\Property(property="player_name", example="Wayne Rooney")
     */
    private $playerName;

    /**
     * The minute when the substitued occured
     * @var integer
     * @SWG\Property(example=68)
     */
    private $elapsed;

    /**
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @param integer $playerId
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = (int) $playerId;
        return $this;
    }

    /**
     * @return the string
     */
    public function getPlayerName()
    {
        return $this->playerName;
    }

    /**
     * @param  $playerName
     */
    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;
        return $this;
    }

    /**
     * @return the integer
     */
    public function getElapsed()
    {
        return $this->elapsed;
    }

    /**
     * @param integer $elapsed
     */
    public function setElapsed($elapsed)
    {
        $this->elapsed = (int) $elapsed;
        return $this;
    }

    public function getPersistanceMap()
    {
        return [
            'player_id' => $this->playerId,
            'player_name' => $this->playerName,
            'elapsed' => $this->elapsed
        ];
    }

    public function jsonSerialize()
    {
        $data = $this->getPersistanceMap();
        if (isset($this->mlContent['name'])) {
            $data['player_name'] = $this->mlContent['name'];
        }
        
        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\MlContainerInterface::getId()
     */
    public function getId()
    {
        return $this->getPlayerId();
    }

    public function getContainerName()
    {
        return 'player';
    }
}