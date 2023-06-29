<?php
namespace Sportal\FootballApi\Dto\Stage;

use Sportal\FootballApi\Dto\IDto;

class StageListInput implements IDto
{

    private $teamId;

    private $tournamentIds;

    /**
     * StageListInput constructor.
     * @param $teamId
     * @param $tournamentId
     */
    public function __construct($teamId = null, array $tournamentIds = [])
    {
        $this->teamId = $teamId;
        $this->tournamentIds = $tournamentIds;
    }

    /**
     * @return integer
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @return mixed
     */
    public function getTournamentIds()
    {
        return $this->tournamentIds;
    }

    /**
     * Checks whether all properties in the filter are empty
     * @return bool
     */
    public function isEmpty()
    {
        foreach (get_object_vars($this) as $property) {
            if (! empty($property)) {
                return false;
            }
        }

        return true;
    }
}