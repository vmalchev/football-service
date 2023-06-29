<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition()
 */
class TeamSeasonStatistics implements \JsonSerializable, Translateable
{
    /**
     *
     * @var integer
     * @SWG\Property(property="team_id")
     */
    private $teamId;

    /**
     *
     * Latest league stage during the TournamentSeason
     * @var TournamentSeasonStage
     * @SWG\Property(property="latest_league_stage")
     */
    private $latestLeagueStage;

    /**
     * Player statistics for the TournamentSeason
     * @var BasicPlayerStats[]
     * @SWG\Property(property="player_statistics")
     */
    private $playerStats;

    /**
     * @return integer
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @param integer $teamId
     */
    public function setTeamId($teamId)
    {
        $this->teamId = (int) $teamId;
        return $this;
    }

    public function getPlayerStats()
    {
        return $this->playerStats;
    }

    public function setPlayerStats(array $playerStats)
    {
        $this->playerStats = $playerStats;
        return $this;
    }

    public function getLatestLeagueStage()
    {
        return $this->latestLeagueStage;
    }

    public function setLatestLeagueStage(TournamentSeasonStage $latestLeagueStage)
    {
        $this->latestLeagueStage = $latestLeagueStage;
        return $this;
    }

    public function addPlayerStats(BasicPlayerStats $stats)
    {
        $this->playerStats[] = $stats;
        return $this;
    }

    public function jsonSerialize()
    {
        $data = [
            'team_id' => $this->teamId,
            'latest_league_stage' => $this->latestLeagueStage
        ];
        
        if (! empty($this->playerStats)) {
            $data['player_statistics'] = $this->playerStats;
        }
        
        return $data;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\Translateable::getMlContentModels()
     */
    public function getMlContentModels()
    {
        $data = $this->latestLeagueStage->getMlContentModels();

        if ($this->playerStats != null) {
            foreach ($this->playerStats as $playerData) {
                $data = array_merge($data, $playerData->getMlContentModels());
            }
        }

        return $data;
    }
}