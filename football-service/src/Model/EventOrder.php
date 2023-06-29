<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Cache\Map\MappableInterface;

class EventOrder implements MappableInterface
{

    private $eventId;

    private $clientCode;

    private $sortorder;

    public function getEventId()
    {
        return $this->eventId;
    }

    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
        return $this;
    }

    public function getClientCode()
    {
        return $this->clientCode;
    }

    public function setClientCode($clientCode)
    {
        $this->clientCode = $clientCode;
        return $this;
    }

    public function getSortorder()
    {
        return $this->sortorder;
    }

    public function setSortorder($sortorder)
    {
        $this->sortorder = (int) $sortorder;
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\Map\MappableInterface::getMapEntries()
     */
    public function getMapEntries()
    {
        return [
            $this->clientCode => [
                $this->eventId => $this->sortorder
            ]
        ];
    }
}