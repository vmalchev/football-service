<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 *  @SWG\Definition(required={"round", "start_time", "end_time"})
 */
class Round implements \JsonSerializable, Translateable
{

    /**
     *
     * @var string
     * @SWG\Property()
     */
    private $round;

    /**
     *
     * @var \DateTime
     * @SWG\Property(property="start_time")
     */
    private $start_time;

    /**
     *
     * @var \DateTime
     * @SWG\Property(property="end_time")
     */
    private $end_time;

    /**
     *
     * @var \Sportal\FootballApi\Model\PartialEvent[]
     * @SWG\Property()
     */
    private $events;

    private $showEvents = false;

    public function addEvent(PartialEvent $event)
    {
        $this->start_time = $this->start_time !== null ? min($this->start_time, $event->getStartTime()) : $event->getStartTime();
        $this->end_time = $this->end_time !== null ? max($this->end_time, $event->getStartTime()) : $event->getStartTime();
        $this->events[] = $event;
    }

    public function getRound()
    {
        return $this->round;
    }

    public function setRound($round)
    {
        $this->round = $round;
        return $this;
    }

    public function getShowEvents()
    {
        return $this->showEvents;
    }

    public function setShowEvents($showEvents)
    {
        $this->showEvents = $showEvents;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $models = [];
        if ($this->showEvents && ! empty($this->events)) {
            foreach ($this->events as $event) {
                $models = array_merge($models, $event->getMlContentModels());
            }
        }
        return $models;
    }

    public function jsonSerialize()
    {
        $arr = [
            'round' => $this->round
        ];
        
        if ($this->start_time !== null) {
            $arr['start_time'] = $this->start_time->format(\DateTime::ATOM);
        }
        
        if ($this->end_time !== null) {
            $arr['end_time'] = $this->end_time->format(\DateTime::ATOM);
        }
        
        if ($this->showEvents && ! empty($this->events)) {
            $arr['events'] = $this->events;
        }
        
        return $arr;
    }
}