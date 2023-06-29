<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *      definition="TournamentSeasonWithStages",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/TournamentSeasonWithTournament"),
 *          @SWG\Schema(type="object", properties={
 *              @SWG\Property(
 *                  type="array",
 *                  property="stages",
 *                  @SWG\Items(ref="#/definitions/TournamentSeasonStage"),
 *                  description="List of stages which take place during the TournamentSeason. If the parent Tournament has regional_league = true, this will usually be one stage with cup = false."
 *             )},
 *             required={"stages"}
 *          )
 *      }
 * )
 *
 * @SWG\Definition(definition="TournamentSeasonWithTournament",
 *                 allOf={
 *                      @SWG\Schema(ref="#/definitions/TournamentSeason"),
 *                      @SWG\Schema(type="object", properties={
 *                          @SWG\Property(ref="#/definitions/Tournament", property="tournament", description="Reference to the parent Tournament")
 *                      }, required={"tournament"})
 *                 })
 *
 * @SWG\Definition(definition="TournamentSeason", required={"id", "name", "active"})
 */
class TournamentSeason implements SurrogateKeyInterface, \JsonSerializable, Translateable
{

    use ContainsMlContent;

    /**
     * Unique identifier of the object
     * @SWG\Property()
     * @var integer
     */
    private $id;

    /**
     * Human readable name of the TournamentSeason
     * @SWG\Property(example="2015/2016")
     * @var string
     */
    private $name;

    /**
     * Whether the season is currently running or whether it is finished
     * @SWG\Property()
     * @var boolean
     */
    private $active;

    /**
     * Reference to the parent tournament
     * @var \Sportal\FootballApi\Model\Tournament
     */
    private $tournament;

    /**
     *
     * @var \Sportal\FootballApi\Model\TournamentSeasonStage
     */
    private $stages;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     *
     * @var integer
     */
    private $tournamentId;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TournamentSeason
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TournamentSeason
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return TournamentSeason
     */
    public function setActive($active)
    {
        $this->active = (boolean) $active;
        
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
     *
     * @param int $id
     * @return \Sportal\FootballApi\Model\TournamentSeason
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set tournament
     *
     * @param integer $id
     *
     * @return TournamentSeason
     */
    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = $tournamentId;
        
        return $this;
    }

    /**
     * Get tournament
     *
     * @return integer
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return array(
            'name' => $this->name,
            'tournament_id' => $this->tournamentId,
            'active' => $this->active
        );
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return array(
            'id' => $this->id
        );
    }

    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'active' => $this->active
        ];
        if ($this->tournament !== null) {
            $data['tournament'] = $this->tournament;
        }
        if ($this->stages !== null && count($this->stages) > 0) {
            $data['stages'] = $this->stages;
        }
        return $this->translateContent($data);
    }

    /**
     * @return TournamentSeasonStage[]
     */
    public function getStages()
    {
        return $this->stages;
    }

    /**
     * @param TournamentSeasonStage[] $stages
     */
    public function setStages(array $stages)
    {
        $this->stages = $stages;
        return $this;
    }

    public function getTournament()
    {
        return $this->tournament;
    }

    public function setTournament($tournament)
    {
        $this->tournament = $tournament;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $tournamentMlContent = [];
        if (!is_null($this->tournament)) {
            $tournamentMlContent = $this->tournament->getMlContentModels();
        }

        return array_merge([
            $this
        ], $tournamentMlContent);
    }
}

