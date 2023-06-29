<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(
 *      definition="StageGroupWithStanding",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/StageGroup"),
 *          @SWG\Schema(type="object", properties={
 *              @SWG\Property(property="standing",
 *                            description="League Standing for the StageGroup if available and requested",
 *                            type="array",
 *                            @SWG\Items(ref="#/definitions/LeagueStandingData"))
 *          })
 *      })
 *
 * @SWG\Definition(
 *      definition="StageGroupWithStandingTournamentSeasonStage",
 *      allOf={
 *          @SWG\Schema(ref="#/definitions/StageGroupWithStanding"),
 *          @SWG\Schema(type="object", properties={
 *              @SWG\Property(property="tournament_season_stage",
 *                            description="Reference to the TournamentSeasonStage the group is a part of",
 *                            ref="#/definitions/TournamentSeasonStage")
 *          },
 *          required={"tournament_season_stage"})
 *      })
 *
 * @SWG\Definition(required={"id", "name"})
 */
class StageGroup implements SurrogateKeyInterface, \JsonSerializable, Translateable, MlContainerInterface
{

    use ContainsMlContent;

    /**
     * Unique resource identifier
     * @var integer
     * @SWG\Property()
     */
    private $id;

    /**
     * Human readable name of the Group
     * @var string
     * @SWG\Property()
     */
    private $name;

    /**
     * Standing for the Group if available
     * @var \Sportal\FootballApi\Model\StandingData[]
     */
    private $standing;

    /**
     * Reference to the TournamentSeasonStage the group is a part of
     * @var \Sportal\FootballApi\Model\TournamentSeasonStage
     */
    private $tournamentSeasonStage;

    /**
     *
     * @var integer
     */
    private $tournamentSeasonStageId;

    /**
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * The order in the stage of the group
     * @var integer|null
     * @SWG\Property()
     */
    private $order_in_stage;

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\SurrogateKeyInterface::getId()
     */
    public function getId()
    {
        return $this->id;
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

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getTournamentSeasonStageId()
    {
        return $this->tournamentSeasonStageId;
    }

    public function setTournamentSeasonStageId($tournamentSeasonStageId)
    {
        $this->tournamentSeasonStageId = $tournamentSeasonStageId;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'name' => $this->name,
            'tournament_season_stage_id' => $this->tournamentSeasonStageId,
            'order_in_stage' => $this->order_in_stage
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
            'name' => $this->name,
            'order_in_stage' => $this->order_in_stage
        ];
        if ($this->standing !== null) {
            $data['standing'] = $this->standing;
        }
        if ($this->tournamentSeasonStage !== null) {
            $data['tournament_season_stage'] = $this->tournamentSeasonStage;
        }
        return $this->translateContent($data);
    }

    public function getStanding()
    {
        return $this->standing;
    }

    public function setStanding($standing)
    {
        $this->standing = $standing;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $data = [];
        if ($this->tournamentSeasonStage !== null) {
            $data = array_merge($data, $this->tournamentSeasonStage->getMlContentModels());
        }
        if (! empty($this->standing)) {
            foreach ($this->standing as $standingData) {
                $data = array_merge($data, $standingData->getMlContentModels());
            }
        }
        return array_merge($data, [
            $this
        ]);
    }

    public function getTournamentSeasonStage()
    {
        return $this->tournamentSeasonStage;
    }

    public function setTournamentSeasonStage(TournamentSeasonStage $tournamentSeasonStage)
    {
        $this->tournamentSeasonStage = $tournamentSeasonStage;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrderInStage(): ?int
    {
        return $this->order_in_stage;
    }

    /**
     * @param int|null $order_in_stage
     * @return StageGroup
     */
    public function setOrderInStage(?int $order_in_stage): StageGroup
    {
        $this->order_in_stage = $order_in_stage;
        return $this;
    }
}