<?php

namespace Sportal\FootballApi\Model;

/**
 * @SWG\Definition(definition="TournamentSeasonStageWithStandingGroups",
 *                 allOf={
 *                      @SWG\Schema(ref="#/definitions/TournamentSeasonStage"),
 *                      @SWG\Schema(type="object", properties={
 *                          @SWG\Property(property="groups",
 *                                        description="List of StageGroup objects in the TournamentSeasonStage. Available if the property stage_groups exists",
 *                                        type="array",
 *                                        @SWG\Items(ref="#/definitions/StageGroupWithStanding"))
 *                      })
 *                 })
 *
 *
 * @SWG\Definition(required={"id", "name", "cup", "tournament_season_id", "tournament_id", "country"})
 */
class TournamentSeasonStage extends PartialStage implements SurrogateKeyInterface
{

    /**
     * Date when the first game of the stage is held
     * @var \DateTime
     * @SWG\Property(property="start_date", type="string", format="date", example="2015-06-20")
     */
    private $startDate;

    /**
     * Date when the final game of the stage is held
     * @var \DateTime
     * @SWG\Property(property="end_date", type="string", format="date", example="2015-06-21")
     */
    private $endDate;

    /**
     * Whether the stage is a qualification stage to a main Tournrament (Champions League Qualification)
     * @var boolean
     * @SWG\Property()
     */
    private $qualification;

    /**
     * Whether the API has live scores for the stage
     * @var boolean
     * @SWG\Property()
     */
    private $live = false;

    /**
     * Number of groups in the stage. Only available if the stage has groups (Champions League Group Stage)
     * @var integer
     * @SWG\Property(property="stage_groups")
     */
    private $stageGroups;

    /**
     * League standing data if available and if cup = false
     * @var \Sportal\FootballApi\Model\StandingData[]
     * @SWG\Property(type="array", @SWG\Items(ref="#/definitions/LeagueStandingData"))
     */
    private $standing;

    /**
     * List of rounds in the Stage if cup = true
     * @var \Sportal\FootballApi\Model\Round[]
     * @SWG\Property()
     */
    private $rounds;

    /**
     * @var \Sportal\FootballApi\Model\StageGroup[]
     */
    private $groups;

    /**
     * @var \Sportal\FootballApi\Model\TournamentSeason
     */
    private $tournamentSeason;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    private ?int $orderInSeason = null;

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TournamentSeasonStage
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return TournamentSeasonStage
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return TournamentSeasonStage
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set qualification
     *
     * @param boolean $qualification
     *
     * @return TournamentSeasonStage
     */
    public function setQualification($qualification)
    {
        $this->qualification = (bool)$qualification;

        return $this;
    }

    /**
     * Get qualification
     *
     * @return boolean
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Set live
     *
     * @param boolean $live
     *
     * @return TournamentSeasonStage
     */
    public function setLive($live)
    {
        $this->live = (bool)$live;

        return $this;
    }

    /**
     * Get live
     *
     * @return boolean
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * Set tournamentSeason
     *
     * @param \Sportal\FootballApi\Model\TournamentSeason $tournamentSeason
     *
     * @return TournamentSeasonStage
     */
    public function setTournamentSeason(\Sportal\FootballApi\Model\TournamentSeason $tournamentSeason = null)
    {
        $this->tournamentSeason = $tournamentSeason;

        return $this;
    }

    /**
     * Get tournamentSeason
     *
     * @return \Sportal\FootballApi\Model\TournamentSeason
     */
    public function getTournamentSeason()
    {
        return $this->tournamentSeason;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return array(
            'name' => $this->getName(),
            'start_date' => ($this->startDate !== null) ? $this->startDate->format('Y-m-d') : null,
            'end_date' => ($this->endDate !== null) ? $this->endDate->format('Y-m-d') : null,
            'cup' => $this->getCup(),
            'qualification' => $this->qualification,
            'live' => $this->live,
            'tournament_season_id' => $this->getTournamentSeasonId(),
            'country_id' => $this->getCountry()->getId(),
            'stage_groups' => $this->stageGroups,
            'confederation' => $this->getConfederation(),
            'order_in_season' => $this->orderInSeason,
            'type' => $this->getType()
        );
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return array(
            'id' => $this->getId()
        );
    }

    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        if ($this->startDate !== null) {
            $data['start_date'] = $this->startDate->format('Y-m-d');
        }

        if ($this->endDate !== null) {
            $data['end_date'] = $this->endDate->format('Y-m-d');
        }

        if ($this->qualification !== null) {
            $data['qualification'] = $this->qualification;
        }

        if ($this->live !== null) {
            $data['live'] = $this->live;
        }

        if ($this->stageGroups !== null) {
            $data['stage_groups'] = $this->stageGroups;
        }

        if (!empty($this->groups)) {
            $data['groups'] = $this->groups;
        }

        if ($this->standing !== null && count($this->standing) > 0) {
            $data['standing'] = $this->standing;
        }

        if (!empty($this->rounds)) {
            $data['rounds'] = $this->rounds;
        }

        return $data;
    }

    public function getTournamentSeasonId()
    {
        return $this->tournamentSeason !== null ? $this->tournamentSeason->getId() : parent::getTournamentSeasonId();
    }

    public function getStanding()
    {
        return $this->standing;
    }

    public function setStanding(array $standing = null)
    {
        $this->standing = $standing;
        return $this;
    }

    public function getStageGroups()
    {
        return $this->stageGroups;
    }

    public function setStageGroups($stageGroups)
    {
        $this->stageGroups = $stageGroups;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function hasGroups()
    {
        return $this->stageGroups !== null && $this->stageGroups > 0;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $data = parent::getMlContentModels();

        if ($this->standing !== null) {
            foreach ($this->standing as $standingData) {
                $data = array_merge($data, $standingData->getMlContentModels());
            }
        }

        if ($this->groups !== null) {
            foreach ($this->groups as $group) {
                $data = array_merge($data, $group->getMlContentModels());
            }
        }

        return $data;
    }

    public function getRounds()
    {
        return $this->rounds;
    }

    public function setRounds(array $rounds = null)
    {
        $this->rounds = $rounds;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrderInSeason(): ?int
    {
        return $this->orderInSeason;
    }

    /**
     * @param int|null $orderInSeason
     * @return TournamentSeasonStage
     */
    public function setOrderInSeason(?int $orderInSeason): TournamentSeasonStage
    {
        $this->orderInSeason = $orderInSeason;
        return $this;
    }


}

