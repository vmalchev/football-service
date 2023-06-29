<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *      definition="TopscorerStandingData",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/StandingData"),
 *          @SWG\Schema(
 *              type="object",
 *              properties={
 *                  @SWG\Property(property="player", ref="#/definitions/PartialPerson", description="Information about the Player"),
 *                  @SWG\Property(property="goals", type="integer", description="Number of goals scored"),
 *                  @SWG\Property(property="played", type="integer", description="Total games played"),
 *                  @SWG\Property(property="assists", type="integer", description="Number of assists"),
 *                  @SWG\Property(property="minutes", type="integer", description="Total number of minutes played"),
 *                  @SWG\Property(property="penalties", type="integer", description="Number of goals scored from the penalty spot"),
 *                  @SWG\Property(property="red_cards", type="integer", description="Number of red cards"),
 *                  @SWG\Property(property="scored_first", type="integer", description="Number of times the player scored first for the team"),
 *                  @SWG\Property(property="yellow_cards", type="integer", description="Number of yellow cards")
 *              },
 *              required={"player", "goals"}
 *          )
 *      }
 * )
 *
 *  @SWG\Definition(
 *      definition="CardlistStandingData",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/StandingData"),
 *          @SWG\Schema(
 *              type="object",
 *              properties={
 *                  @SWG\Property(property="player", ref="#/definitions/PartialPerson", description="Information about the Player"),
 *                  @SWG\Property(property="red_cards", type="integer", description="Number of red cards"),
 *                  @SWG\Property(property="total_cards", type="integer", description="Total number of cards received"),
 *                  @SWG\Property(property="yellow_cards", type="integer", description="Total number of yellow cards"),
 *                  @SWG\Property(property="first_yellow_cards", type="integer", description="Number of times the Player has been booked, with a first yellow card"),
 *              },
 *              required={"player", "red_cards", "total_cards", "yellow_cards", "first_yellow_cards"}
 *          )
 *      }
 * )
 *
 * @SWG\Definition(
 *      definition="LeagueStandingData",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/StandingData"),
 *          @SWG\Schema(
 *              type="object",
 *              properties={
 *                  @SWG\Property(property="team", ref="#/definitions/PartialTeamWithForm", description="Information about the Team (and TeamForm) in the current rank"),
 *                  @SWG\Property(property="wins", type="integer", description="Games won"),
 *                  @SWG\Property(property="draws", type="integer", description="Games drawn"),
 *                  @SWG\Property(property="played", type="integer", description="Total games played"),
 *                  @SWG\Property(property="points", type="integer", description="Number of points"),
 *                  @SWG\Property(property="defeits", type="integer", description="Games lost"),
 *                  @SWG\Property(property="goals_for", type="integer", description="Total number of goals scored"),
 *                  @SWG\Property(property="goals_against", type="integer", description="Total number of goals conceded")
 *              },
 *              required={"wins", "draws", "played", "points", "defeits", "goals_for", "goals_against"}
 *          )
 *      }
 * )
 *
 * @SWG\Definition(required={"rank", "team"})
 */
class StandingData implements SurrogateKeyInterface, \JsonSerializable, Comparable, JsonColumnContainerInterface,
    Translateable
{

    /**
     * @var integer
     */
    private $id;

    /**
     * Position of the team/player in the standing
     * @var integer
     * @SWG\Property()
     */
    private $rank;

    /**
     * Reference to the team in the Standing (for league) or the Team for which the Player competes (topscorer, cardlist)
     * @var \Sportal\FootballApi\Model\PartialTeam
     * @SWG\Property()
     */
    private $team;

    /**
     * List of applicable rules to the current standing rank
     * @var StandingRule[]
     * @SWG\Property(property="rules")
     */
    private $standingRule;

    /**
     * Reference to the Player who is in the standing. Available for topscorer and cardlist.
     * @var \Sportal\FootballApi\Model\PartialPerson
     */
    private $player;

    /**
     * @var array
     */
    private $data;

    /**
     * @var integer
     */
    private $standingId;

    /**
     * Set rank
     *
     * @param integer $rank
     *
     * @return StandingData
     */
    public function setRank($rank)
    {
        $this->rank = (int) $rank;
        
        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return StandingData
     */
    public function setData($data)
    {
        $this->data = $data;
        
        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
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
     * @return StandingData
     */
    public function setPlayer(\Sportal\FootballApi\Model\PartialPerson $player = null)
    {
        $this->player = $player;
        
        return $this;
    }

    /**
     * Get player
     *
     * @return \Sportal\FootballApi\Model\s
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set team
     *
     * @param \Sportal\FootballApi\Model\PartialTeam $team
     *
     * @return StandingData
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
     * Add standingRule
     *
     * @param \Sportal\FootballApi\Model\StandingRule $standingRule
     *
     * @return StandingData
     */
    public function addStandingRule(\Sportal\FootballApi\Model\StandingRule $standingRule)
    {
        if ($this->standingRule == null) {
            $this->standingRule = [];
        }
        $this->standingRule[] = $standingRule;
        
        return $this;
    }

    /**
     * Get standingRule
     *
     * @return StandingRule[]
     */
    public function getStandingRule()
    {
        return $this->standingRule;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'standing_id' => $this->standingId,
            'team_id' => $this->team->getId(),
            'player_id' => $this->getPlayerId(),
            'rank' => $this->rank,
            'data' => json_encode($this->data)
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

    /**
     * @return integer
     */
    public function getStandingId()
    {
        return $this->standingId;
    }

    /**
     * @param integer $standingId
     */
    public function setStandingId($standingId)
    {
        $this->standingId = $standingId;
        return $this;
    }

    public function jsonSerialize()
    {
        $data = [
            'rank' => $this->rank,
            'team' => $this->team
        ];
        if ($this->player !== null) {
            $data['player'] = $this->player;
        }
        foreach ($this->data as $key => $value) {
            $data[$key] = $value;
        }
        
        if (! empty($this->standingRule)) {
            $data['rules'] = $this->standingRule;
        }
        
        return $data;
    }

    public function getPlayerId()
    {
        return ($this->player !== null) ? $this->player->getId() : null;
    }

    public function equals($other)
    {
        return $this->getStandingId() == $other->getStandingId() &&
             $this->getTeam()->getId() == $other->getTeam()->getId() && $this->getPlayerId() == $other->getPlayerId();
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\JsonColumnContainerInterface::getJsonData()
     */
    public function getJsonData()
    {
        return [
            'data' => $this->data
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\JsonColumnContainerInterface::getChangedJsonColumns()
     */
    public function getChangedJsonColumns(JsonColumnContainerInterface $updated)
    {
        $updatedData = $updated->getJsonData();
        if ($updatedData['data'] != $this->data) {
            return [
                'data'
            ];
        }
        return [];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\JsonColumnContainerInterface::updateJsonColumns()
     */
    public function updateJsonColumns(JsonColumnContainerInterface $updated)
    {
        $this->setData($updated->getJsonData()['data']);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $data = $this->team->getMlContentModels();
        
        if ($this->player !== null) {
            $data[] = $this->player;
        }

        if ($this->standingRule !== null) {
            $data = array_merge($data, $this->standingRule);
        }

        return $data;
    }
}

