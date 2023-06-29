<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Util\NameUtil;
use Swagger\Annotations as SWG;

/**
 *
 * @SWG\Definition(definition="TeamPlayerSeasonStatistics",
 *                 allOf={
 *                      @SWG\Schema(ref="#/definitions/TournamentSeasonWithTournament"),
 *                      @SWG\Schema(type="object", properties={
 *                          @SWG\Property(property="items", type="array", @SWG\Items(ref="#/definitions/TeamPlayerStatistics"))
 *                      })
 *                 })
 *
 * @SWG\Definition(definition="TeamPlayerStatistics",
 *                 allOf={
 *                      @SWG\Schema(ref="#/definitions/PlayerStatsExtended"),
 *                      @SWG\Schema(type="object", properties={
 *                          @SWG\Property(property="player", ref="#/definitions/Player"),
 *                          @SWG\Property(property="team", ref="#/definitions/Team"),
 *                          @SWG\Property(property="shirt_number", type="integer")
 *                      }, required={"player"})
 *                 })
 *
 * @SWG\Definition(definition="PlayerStatsBasic", properties={
 *                  @SWG\Property(property="goals", type="integer", description="Total goals scored"),
 *                  @SWG\Property(property="played", type="integer", description="Total games played"),
 *                  @SWG\Property(property="minutes", type="integer", description="Total minutes played"),
 *                  @SWG\Property(property="red_cards", type="integer", description="Number of red cards"),
 *                  @SWG\Property(property="yellow_cards", type="integer", description="Number of yellow cards")},
 *                  @SWG\Property(property="assists", type="integer", description="Number of assists"),
 *                  required={"goals", "played", "minutes", "red_cards", "yellow_cards"})
 *
 * @SWG\Definition(definition="PlayerStatsExtended", allOf={
 *          @SWG\Schema(ref="#/definitions/PlayerStatsBasic"),
 *          @SWG\Schema(type="object", properties={
 *                                     @SWG\Property(property="conceded", type="integer", description="Goals conceded (Goalkeeper)"),
 *                                     @SWG\Property(property="substitute", type="integer", description="Games started as a substitute"),
 *                                     @SWG\Property(property="cleansheets", type="integer", description="Games without allowing a goal (Goalkeeper)"),
 *                                     @SWG\Property(property="substitute_in", type="integer", description="Games where the player has come on"),
 *                                     @SWG\Property(property="substitute_out", type="integer", description="Games where the player has come off"),
 *                                     @SWG\Property(property="minutes_substitute", type="integer", description="Minutes played after coming on as a substitute")
 *                                     })
 *          })
 *
 * PlayerStatistics
 * @SWG\Definition(allOf={@SWG\Schema(ref="#/definitions/PlayerStatsExtended")}, required={"player", "team", "tournament_season"})
 */
class PlayerStatistics implements ModelInterface, \JsonSerializable, Comparable, Translateable
{

    /**
     * @var array
     */
    private $statistics;

    /**
     *
     * @var SurrogateKeyInterface
     * @SWG\Property(ref="#/definitions/TournamentSeasonWithTournament", property="tournament_season")
     */
    private $tournament;

    /**
     * Player Information
     * @var \Sportal\FootballApi\Model\PartialPerson
     * @SWG\Property()
     */
    private $player;

    /**
     * Team for which the Player competes
     * @var \Sportal\FootballApi\Model\PartialTeam
     * @SWG\Property()
     */
    private $team;

    /**
     * Shirt number of the player during the TournamentSeason
     * @var integer
     * @SWG\Property()
     */
    private $shirtNumber;

    /**
     * Position of the player during the TournamentSeason
     * @var string
     * @SWG\Property()
     */
    private $position;

    private $display = [
        'player',
        'team'
    ];

    /**
     * Set statistics
     *
     * @param array $statistics
     *
     * @return PlayerStatistics
     */
    public function setStatistics($statistics)
    {
        $this->statistics = $statistics;
        
        return $this;
    }

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStatistics()
    {
        return $this->statistics;
    }

    /**
     * Set player
     *
     * @param \Sportal\FootballApi\Model\PartialPerson $player
     *
     * @return PlayerStatistics
     */
    public function setPlayer(\Sportal\FootballApi\Model\PartialPerson $player)
    {
        $this->player = $player;
        
        return $this;
    }

    /**
     * Get player
     *
     * @return \Sportal\FootballApi\Model\PartialPerson
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return PartialTeam
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param PartialTeam $team
     *
     * @return PlayerStatistics
     */
    public function setTeam(\Sportal\FootballApi\Model\PartialTeam $team)
    {
        $this->team = $team;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        $data = $this->getPrimaryKeyMap();
        $data['statistics'] = json_encode($this->statistics);
        $data['shirt_number'] = $this->shirtNumber;
        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'player_id' => $this->player->getId(),
            'team_id' => $this->team->getId(),
            'tournament_entity' => NameUtil::persistanceName(get_class($this->tournament)),
            'tournament_entity_id' => $this->tournament->getId()
        ];
    }

    public function jsonSerialize()
    {
        $data = [
            NameUtil::persistanceName(get_class($this->tournament)) => $this->tournament
        ];
        
        foreach ($this->display as $prop) {
            $data[$prop] = $this->{$prop};
        }
        
        foreach ($this->statistics as $key => $value) {
            $data[$key] = $value;
        }
        
        if ($this->shirtNumber !== null) {
            $data['shirt_number'] = $this->shirtNumber;
        }

        if ($this->position !== null) {
            $data['position'] = $this->position;
        }
        
        return $data;
    }

    public function add(array $otherStats)
    {
        foreach ($this->statistics as $key => $value) {
            if (isset($otherStats[$key])) {
                $this->statistics[$key] = $value + $otherStats[$key];
            }
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Comparable::equals()
     */
    public function equals($other)
    {
        return $this->getPrimaryKeyMap() == $other->getPrimaryKeyMap();
    }

    /**
     * @return SurrogateKeyInterface
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * @param SurrogateKeyInterface $tournament
     */
    public function setTournament(SurrogateKeyInterface $tournament)
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
        $data = [];
        
        foreach ($this->display as $prop) {
            $data[] = $this->{$prop};
        }
        
        if ($this->tournament instanceof TournamentSeason && ($tournament = $this->tournament->getTournament()) !== null) {
            $data = array_merge(
                $data,
                $tournament->getMlContentModels(),
                $this->tournament->getMlContentModels()
            );
        }
        
        return $data;
    }

    public function getDisplay()
    {
        return $this->display;
    }

    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }

    public function getShirtNumber()
    {
        return $this->shirtNumber;
    }

    public function setShirtNumber($shirtNumber)
    {
        $this->shirtNumber = $shirtNumber;
        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    public function getAssists()
    {
        return $this->getStat('assists');
    }

    public function getGoals()
    {
        return $this->getStat('goals');
    }

    public function getReds()
    {
        return $this->getStat('red_cards');
    }

    public function getYellows()
    {
        return $this->getStat('yellow_cards');
    }

    private function getStat($prop)
    {
        return isset($this->statistics[$prop]) ? $this->statistics[$prop] : null;
    }
}

