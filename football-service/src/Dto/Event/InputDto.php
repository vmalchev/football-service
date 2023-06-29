<?php
namespace Sportal\FootballApi\Dto\Event;


use Sportal\FootballApi\Dto\IDto;

class InputDto implements IDto
{
    private $eventOrder;
    private $fromTime;
    private $toTime;
    private $teamId;
    private $statusTypes;
    private $matchIds;
    private $tournamentIds;
    private $seasonIds;

    public function __construct($eventOrder, $fromTime, $toTime, $teamId, $statusTypes, $matchIds, $tournamentIds, $seasonIds) {
        $this->eventOrder = $eventOrder;
        $this->fromTime = $fromTime;
        $this->toTime = $toTime;
        $this->teamId = $teamId;
        $this->statusTypes = $statusTypes;
        $this->matchIds = $matchIds;
        $this->tournamentIds = $tournamentIds;
        $this->seasonIds = $seasonIds;
    }

    /**
     * @return mixed
     */
    public function getEventOrder()
    {
        return $this->eventOrder;
    }

    /**
     * @return mixed
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * @return mixed
     */
    public function getToTime()
    {
        return $this->toTime;
    }

    /**
     * @return mixed
     */
    public function getTeamId()
    {
        return $this->teamId;
    }
    /**
     * @return mixed
     */
    public function getStatusTypes()
    {
        return $this->statusTypes;
    }

    /**
     * @return array
     */
    public function getMatchIds()
    {
        return $this->matchIds;
    }

    /**
     * @return mixed
     */
    public function getTournamentIds()
    {
        return $this->tournamentIds;
    }

    /**
     * @return mixed
     */
    public function getSeasonIds()
    {
        return $this->seasonIds;
    }
}
