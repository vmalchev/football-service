<?php
namespace Sportal\FootballApi\Repository;

use Sportal\FootballApi\Model\Standing;
use Sportal\FootballApi\Database\Database;
use Sportal\FootballApi\Model\TournamentSeason;
use Sportal\FootballApi\Model\Tournament;
use Sportal\FootballApi\Model\StandingData;
use Sportal\FootballApi\Model\TournamentSeasonStage;
use Sportal\FootballApi\Model\TeamStatistics;
use Sportal\FootballApi\Model\TeamSeasonStatistics;
use Sportal\FootballApi\Model\BasicPlayerStats;

class TeamStatisticsRepository
{

    protected $stageRepository;

    protected $standingDataRepository;

    protected $playerStats;

    protected $db;

    public function __construct(Database $db, TournamentSeasonStageRepository $stageRepository,
        StandingDataRepository $standingData, PlayerStatisticsRepository $playerStats)
    {
        $this->db = $db;
        $this->stageRepository = $stageRepository;
        $this->standingDataRepository = $standingData;
        $this->playerStats = $playerStats;
    }

    public function getStageJoin()
    {
        $factory = $this->db->getJoinFactory();
        $stageJoin = $factory->createInner($this->stageRepository->getModelClass(),
            $this->stageRepository->getColumns());
        
        foreach ($this->stageRepository->getJoin() as $joinArr) {
            $childJoin = $factory->createInner($joinArr['className'], $joinArr['columns']);
            if ($joinArr['className'] == TournamentSeason::class) {
                $tournamentJoin = $factory->createInner(Tournament::class, []);
                $childJoin->addChild($tournamentJoin);
            }
            $stageJoin->addChild($childJoin);
        }
        return $stageJoin;
    }

    public function getLatestLeague($teamId)
    {
        $query = $this->db->createQuery()
            ->addJoin($this->getStageJoin())
            ->addOrderByNullsLast('start_date', 'desc', TournamentSeasonStage::class)
            ->setMaxResults(1);
        
        $expr = $query->andX();
        $expr->eq('team_id', $teamId)
            ->eq('cup', 0, TournamentSeasonStage::class)
            ->eq('regional_league', 1, Tournament::class);
        
        $query->from('tournament_season_stage_team')->where($expr);
        
        return $this->db->getSingleResult($query,
            function ($row) {
                return $this->stageRepository->buildObject($row['tournament_season_stage']);
            });
    }

    public function getLatestLeagueStats($teamId, $singleStanding = false)
    {
        $league = $this->getLatestLeague($teamId);
        
        if ($league !== null) {
            $stats = (new TeamSeasonStatistics())->setTeamId($teamId)->setLatestLeagueStage($league);
            $standing = ($singleStanding) ? $this->standingDataRepository->findTeamStanding($league, $teamId) : $this->standingDataRepository->findLeagueStanding(
                $league);
            $league->setStanding($standing);
            $playerStats = $this->playerStats->findTeamSeasonStatistics($teamId, $league->getTournamentSeasonId());
            foreach ($playerStats as $playerStat) {
                $stats->addPlayerStats(new BasicPlayerStats($playerStat));
            }
            return $stats;
        }
    }
}