<?php
namespace Sportal\FootballApi\Model;

/**
 * @SWG\Definition(definition="PartialTournamentSeasonStage", required={"id", "name", "cup", "tournament_season_id", "tournament_id", "country"})
 */
class PartialStage implements MlContainerInterface, \JsonSerializable, Translateable
{
    use ContainsMlContent;

    /**
     * Unique identifier
     * @var integer
     * @SWG\Property(example=1)
     */
    private $id;

    /**
     * Human readable name of the TournamentSeasonStage
     * @var string
     * @SWG\Property(example="Champions League Final Stages")
     */
    private $name;

    /**
     * Whether the stage is a cup such as Champions League Knockout stage, World Cup Final stages, etc
     * @var boolean
     * @SWG\Property()
     */
    private $cup = false;

    /**
     * Id of the TournamentSeason in which the stage is part of
     * @var integer
     * @SWG\Property(property="tournament_season_id")
     */
    private $tournamentSeasonId;

    /**
     * Id of the Tournament in whicth the stage is part of
     * @var integer
     * @SWG\Property(property="tournament_id")
     */
    private $tournamentId;

    /**
     * Reference to the Country where the TournamentSeasonStage is held
     * @var \Sportal\FootballApi\Model\Country
     * @SWG\Property()
     */
    private $country;

    /**
     * Indicates which confederation the stage is part of (for World Cup qualif.)
     * @var string
     * @SWG\Property()
     */
    private $confederation;

    private ?string $type;

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
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PartialStage
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
     * Set cup
     *
     * @param boolean $cup
     *
     * @return PartialStage
     */
    public function setCup($cup)
    {
        $this->cup = (bool) $cup;
        
        return $this;
    }

    /**
     * Get cup
     *
     * @return boolean
     */
    public function getCup()
    {
        return $this->cup;
    }

    /**
     *
     * @return integer
     */
    public function getTournamentSeasonId()
    {
        return $this->tournamentSeasonId;
    }

    public function setTournamentSeasonId($tournamentSeasonId)
    {
        $this->tournamentSeasonId = (int) $tournamentSeasonId;
        return $this;
    }

    /**
     *
     * @return \Sportal\FootballApi\Model\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(\Sportal\FootballApi\Model\Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getTournamentId()
    {
        return $this->tournamentId;
    }

    public function setTournamentId($tournamentId)
    {
        $this->tournamentId = (int) $tournamentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfederation()
    {
        return $this->confederation;
    }

    /**
     * @param string $confederation
     */
    public function setConfederation($confederation)
    {
        $this->confederation = $confederation;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): PartialStage
    {
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'cup' => $this->cup,
            'tournament_season_id' => $this->tournamentSeasonId,
            'tournament_id' => $this->tournamentId,
            'country' => $this->country
        ];
        
        if (! empty($this->confederation)) {
            $data['confederation'] = $this->confederation;
        }
        
        return $this->translateContent($data);
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        return [
            $this,
            $this->country
        ];
    }

    public function clonePartial()
    {
        $stage = new PartialStage();
        $this->cloneInto($stage);
        return $stage;
    }

    public function cloneInto(PartialStage $stage)
    {
        $stage->setId($this->getId())
            ->setName($this->getName())
            ->setCup($this->getCup())
            ->setTournamentSeasonId($this->getTournamentSeasonId())
            ->setTournamentId($this->getTournamentId())
            ->setCountry($this->getCountry());
    }

    public function getContainerName()
    {
        return 'tournament_season_stage';
    }
}

