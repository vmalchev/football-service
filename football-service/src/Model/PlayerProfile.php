<?php

namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Asset\Assetable;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(allOf={
 *      @SWG\Schema(ref="#/definitions/Player")
 * })
 */
class PlayerProfile implements \JsonSerializable, Translateable, Assetable
{

    private $player;

    /**
     * Information about the current league the player is in
     * @var TournamentSeason
     * @SWG\Property(ref="#/definitions/TournamentSeasonWithTournament", property="current_league")
     */
    private $currentLeague;

    /**
     * Information about the club the Player is currently playing in
     * @var \Sportal\FootballApi\Model\TeamPlayer
     * @SWG\Property(ref="#/definitions/PlayerTeam")
     */
    private $club;

    /**
     * Information about the national team the Player is currently playing in
     * @var \Sportal\FootballApi\Model\TeamPlayer
     * @SWG\Property(ref="#/definitions/PlayerTeam")
     */
    private $nationalTeam;

    /**
     * Display name of the player position
     * @var string
     * @SWG\Property(property="position_display")
     */
    private $positionDisplay;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $models = $this->player->getMlContentModels();
        if ($this->club !== null) {
            $models = array_merge($models, $this->club->getMlContentModels());
        }
        if ($this->currentLeague !== null) {
            $models = array_merge($models, $this->currentLeague->getMlContentModels());
        }
        if ($this->nationalTeam !== null) {
            $models = array_merge($models, $this->nationalTeam->getMlContentModels());
        }
        return $models;
    }

    public function jsonSerialize()
    {
        $data = $this->player->jsonSerialize();

        if ($this->club !== null) {
            $data['club'] = $this->club;
        }

        if ($this->nationalTeam !== null) {
            $data['national_team'] = $this->nationalTeam;
        }

        if ($this->currentLeague !== null) {
            $data['current_league'] = $this->currentLeague;
        }

        if ($this->positionDisplay !== null) {
            $data['position_display'] = $this->positionDisplay;
        }

        return $data;
    }

    public function getClub()
    {
        return $this->club;
    }

    public function setClub(TeamPlayer $club = null)
    {
        $this->club = $club;
        return $this;
    }

    public function getNationalTeam()
    {
        return $this->nationalTeam;
    }

    public function setNationalTeam(TeamPlayer $nationalTeam = null)
    {
        $this->nationalTeam = $nationalTeam;
        return $this;
    }


    public function getCurrentLeague()
    {
        return $this->currentLeague;
    }

    public function setCurrentLeague(TournamentSeason $currentLeague = null)
    {
        $this->currentLeague = $currentLeague;
        return $this;
    }

    public function getPositionDisplay()
    {
        return $this->positionDisplay;
    }

    public function setPositionDisplay($positionDisplay)
    {
        $this->positionDisplay = $positionDisplay;
        return $this;
    }

    public function getAssetFilename($type)
    {
        return $this->player->getAssetFilename($type);
    }

    public function setAssetFilename($type, $filename)
    {
        return $this->player->setAssetFilename($type, $filename);
    }

    public function generateAssetName($type)
    {
        return $this->player->generateAssetName($type);
    }

    public function isSupported($type)
    {
        return $this->player->isSupported($type);
    }

    public function hasAsset($type)
    {
        return $this->player->hasAsset($type);
    }

    public function getAssetModelName()
    {
        return $this->player->getAssetModelName();
    }

    public function getAssetTypes()
    {
        return $this->player->getAssetTypes();
    }

    public function getId()
    {
        return $this->player->getId();
    }
}