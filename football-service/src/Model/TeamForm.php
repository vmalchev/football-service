<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(required={"outcome"})
 */
class TeamForm implements \JsonSerializable, Translateable
{

    const WIN = 'W';

    const LOSE = 'L';

    const DRAW = 'D';

    /**
     *
     * @var integer
     */
    private $teamId;

    /**
     *
     * Information about the Event in the Form guide
     * @var \Sportal\FootballApi\Model\PartialEvent
     * @SWG\Property()
     */
    private $event;

    /**
     * Indicates outcome of the event for the given team
     * @var string
     * @SWG\Property(enum={"W", "D", "L"})
     */
    private $outcome;

    private $showEvent = false;

    public function getTeamId()
    {
        return $this->teamId;
    }

    public function setTeamId($teamId)
    {
        $this->teamId = (int) $teamId;
        $this->updateOutcome();
        return $this;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent(PartialEvent $event)
    {
        $this->event = $event;
        $this->updateOutcome();
        return $this;
    }

    public function getOutcome()
    {
        return $this->outcome;
    }

    private function updateOutcome()
    {
        if ($this->event !== null && $this->teamId !== null) {
            $homeTeam = $this->isHomeTeam();
            if ($this->event->isHomeWin()) {
                $this->outcome = ($homeTeam) ? static::WIN : static::LOSE;
            } elseif ($this->event->isAwayWin()) {
                $this->outcome = ($homeTeam) ? static::LOSE : static::WIN;
            } elseif ($this->event->isDraw()) {
                $this->outcome = static::DRAW;
            }
        }
    }

    private function isHomeTeam()
    {
        if ($this->event->getHomeId() === $this->teamId) {
            return true;
        } elseif ($this->event->getAwayId() === $this->teamId) {
            return false;
        }
        
        throw new \LogicException(
            'Team ' . $this->teamId . ' is not home/away in ' . $this->event->getId() . " " .
                 $this->event->getHomeTeam()->getName() . "-" . $this->event->getAwayTeam()->getName());
    }

    public function jsonSerialize()
    {
        if ($this->showEvent) {
            return [
                'outcome' => $this->outcome,
                'event' => $this->event
            ];
        }
        return [
            'outcome' => $this->outcome
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        if ($this->showEvent) {
            return $this->event->getMlContentModels();
        }
        return [];
    }

    public function setShowEvent($showEvent)
    {
        $this->showEvent = (boolean) $showEvent;
        return $this;
    }
}