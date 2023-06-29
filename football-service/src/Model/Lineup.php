<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Database\ModelInterface as DbModel;
use Sportal\FootballApi\Cache\Entity\CacheEntityInterface;
use Sportal\FootballApi\Cache\Index\IndexableInterface;
use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(definition="TeamLineup", type="object", properties={
 *      @SWG\Property(property="team_id", type="integer", description="resource id of the team"),
 *      @SWG\Property(property="players", description="List of players involved for the team", type="array", @SWG\Items(ref="#/definitions/EventPlayer")),
 *      @SWG\Property(property="coach", description="Information about the team's Coach", ref="#/definitions/PartialPerson"),
 *      @SWG\Property(property="formation", description="Lineup formation", example="4-3-3", type="string")
 * })
 */

/**
 *
 * @SWG\Definition(required={"event_id", "home_team", "away_team"})
 */
class Lineup implements DbModel, CacheEntityInterface, IndexableInterface, \JsonSerializable, Translateable
{
    use UpdateTimestamp;

    const UPDATED_AT_INDEX = 'updated_at';

    /**
     * Id of the event for which the lineup is for
     * @var integer
     * @SWG\Property(property="event_id")
     */
    private $eventId;

    /**
     * Whether the lineup is officially confirmed. Should be considered probable if != true
     * @var boolean
     * @SWG\Property()
     */
    private $confirmed = false;

    /**
     * Lineup information for home team
     * @var object
     * @SWG\Property(property="home_team", ref="#/definitions/TeamLineup")
     */
    private $homeTeam;

    /**
     * Lineup information for away team
     * @var object
     * @SWG\Property(property="away_team", ref="#/definitions/TeamLineup")
     */
    private $awayTeam;

    public function getEventId()
    {
        return $this->eventId;
    }

    public function setEventId($eventId)
    {
        $this->eventId = (int) $eventId;
        return $this;
    }

    public function getHomeFormation()
    {
        return $this->getProperty($this->homeTeam, 'formation');
    }

    public function setHomeFormation($homeFormation)
    {
        $this->homeTeam['formation'] = $homeFormation;
        return $this;
    }

    public function getAwayFormation()
    {
        return $this->getProperty($this->awayTeam, 'formation');
    }

    public function setAwayFormation($awayFormation)
    {
        $this->awayTeam['formation'] = $awayFormation;
        return $this;
    }

    /**
     *
     * @return PartialPerson
     */
    public function getHomeCoach()
    {
        return $this->getProperty($this->homeTeam, 'coach');
    }

    public function setHomeCoach(PartialPerson $homeCoach = null)
    {
        $this->homeTeam['coach'] = $homeCoach;
        return $this;
    }

    /**
     *
     * @return PartialPerson
     */
    public function getAwayCoach()
    {
        return $this->getProperty($this->awayTeam, 'coach');
    }

    public function setAwayCoach(PartialPerson $awayCoach)
    {
        $this->awayTeam['coach'] = $awayCoach;
        return $this;
    }

    public function getConfirmed()
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed)
    {
        $this->confirmed = (boolean) $confirmed;
        return $this;
    }

    public function setHomeTeamId($homeId)
    {
        $this->homeTeam['team_id'] = (int) $homeId;
        return $this;
    }

    public function setAwayTeamId($awayId)
    {
        $this->awayTeam['team_id'] = (int) $awayId;
        return $this;
    }

    public function addPlayer(EventPlayer $player)
    {
        if ($player->getHomeTeam()) {
            $team = & $this->homeTeam;
        } else {
            $team = & $this->awayTeam;
        }
        $team['players'][] = $player;
        $position = $player->getPositionX();
        if ($position !== null) {
            if (! isset($team['positions'])) {
                $team['positions'] = 0;
            }
            $team['positions'] ++;
            $player->setPositionNumber($team['positions']);
        }
    }

    private function getProperty(array $team = null, $name)
    {
        return isset($team[$name]) ? $team[$name] : null;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Database\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        $homeCoach = $this->getHomeCoach();
        $awayCoach = $this->getAwayCoach();
        return [
            'event_id' => $this->eventId,
            'home_formation' => $this->getHomeFormation(),
            'away_formation' => $this->getAwayFormation(),
            'home_coach_id' => $homeCoach !== null ? $homeCoach->getId() : null,
            'away_coach_id' => $awayCoach !== null ? $awayCoach->getId() : null,
            'confirmed' => $this->confirmed
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Database\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'event_id' => $this->eventId
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\Index\IndexableInterface::getSortedIndecies()
     */
    public function getSortedIndecies()
    {
        return [
            'updated_at' => $this->getUpdatedAt()
        ];
    }

    public function jsonSerialize()
    {
        unset($this->homeTeam['positions']);
        unset($this->awayTeam['positions']);
        
        return array_merge(
            [
                'event_id' => $this->eventId,
                'confirmed' => $this->confirmed,
                'home_team' => $this->homeTeam,
                'away_team' => $this->awayTeam
            ], $this->jsonUpdatedAt());
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $models = [];
        
        $teams = [
            $this->homeTeam,
            $this->awayTeam
        ];
        
        foreach ($teams as $team) {
            if (! empty($team['players'])) {
                foreach ($team['players'] as $player) {
                    foreach ($player->getMlContentModels() as $model) {
                        $models[] = $model;
                    }
                }
            }
            
            if (isset($team['coach'])) {
                foreach ($team['coach']->getMlContentModels() as $model) {
                    $models[] = $model;
                }
            }
        }
        
        return $models;
    }
}