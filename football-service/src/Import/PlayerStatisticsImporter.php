<?php
namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Model\PlayerStatistics;
use Sportal\FootballApi\Model\SurrogateKeyInterface;
use Sportal\FootballApi\Model\TournamentSeason;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\PlayerStatisticsRepository;
use Sportal\FootballApi\Repository\StageGroupRepository;
use Sportal\FootballApi\Repository\TournamentSeasonStageRepository;
use Sportal\FootballFeedCommon\PlayerStatisticsFeedInterface;
use Sportal\FootballApi\Util\NameUtil;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballApi\Repository\TeamPlayerRepository;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Model\Team;
use Sportal\FootballApi\Util\ArrayUtil;
use Sportal\FootballApi\Model\Player;
use Sportal\FootballApi\Model\TeamPlayer;

class PlayerStatisticsImporter
{

    /**
     *
     * @var PlayerStatisticsFeedInterface[]
     */
    protected $feeds;

    /**
     *
     * @var PlayerStatisticsRepository
     */
    protected $repository;

    /**
     *
     * @var TeamPlayerRepository
     */
    protected $teamPlayerRepository;

    /**
     *
     * @var TournamentSeasonStageRepository
     */
    protected $stageRepository;

    /**
     *
     * @var StageGroupRepository
     */
    protected $groupRepository;

    /**
     *
     * @var MappingRepositoryContainer
     */
    protected $mappings;

    protected $logger;

    protected $dispatcher;

    /**
     *
     * @var TeamPlayer
     */
    private $teamPlayers;

    function __construct(PlayerStatisticsRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, FeedContainer $feeds, TeamPlayerRepository $teamPlayer,
        TournamentSeasonStageRepository $stageRepository, StageGroupRepository $groupRepository,
        JobDispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->feeds = $feeds;
        $this->logger = $logger;
        $this->mappings = $mappings;
        $this->teamPlayerRepository = $teamPlayer;
        $this->stageRepository = $stageRepository;
        $this->groupRepository = $groupRepository;
        $this->dispatcher = $dispatcher;
    }

    private function createPlayerStatObject(TeamPlayer $teamPlayer, SurrogateKeyInterface $tournament, array $statArray)
    {
        return $this->repository->createObject(
            [
                'tournament' => $tournament,
                'player' => $teamPlayer->getPerson(),
                'team' => $teamPlayer->getTeam(),
                'statistics' => $statArray['data'],
                'shirt_number' => (isset($statArray['shirt_number']) ? $statArray['shirt_number'] : $teamPlayer->getShirtNumber())
            ]);
    }

    private function getTournamentStats(SurrogateKeyInterface $tournament, $sourceName = null)
    {
        $feedId = $this->mappings->get($sourceName)->getRemoteId(get_class($tournament), $tournament->getId());
        $statIndex = [];
        if ($feedId !== null) {
            $stats = $this->feeds[$sourceName]->getPlayerStatistics(get_class($tournament), $feedId);
            if (! empty($stats)) {
                foreach ($stats as $statArray) {
                    $teamPlayer = $this->findTeamPlayer($statArray['player_id'], $statArray['team_id'], $sourceName);
                    if ($teamPlayer !== null) {
                        $index = $teamPlayer->getPerson()->getId() . "-" . $teamPlayer->getTeam()->getId();
                        $statIndex[$index] = $this->createPlayerStatObject($teamPlayer, $tournament, $statArray);
                    }
                }
            }
        }
        return $statIndex;
    }

    private function findTeamPlayers($feedId, $sourceName)
    {
        if (($teamId = $this->mappings->get($sourceName)->getOwnId(Team::class, $feedId)) === null) {
            $this->dispatcher->dispatch('Import\Team',
                [
                    $feedId,
                    $sourceName
                ]);
            return null;
        } else {
            if (! isset($this->teamPlayers[$teamId])) {
                $teamPlayers = $this->teamPlayerRepository->findByTeam($teamId);
                if (empty($teamPlayers)) {
                    $this->dispatcher->dispatch('Import\TeamPlayer',
                        [
                            $teamId,
                            $sourceName
                        ]);
                    return null;
                } else {
                    $this->teamPlayers[$teamId] = ArrayUtil::indexMulti($teamPlayers,
                        function ($teamPlayer) {
                            return $teamPlayer->getPerson()->getId();
                        });
                }
            }
            return $teamId;
        }
    }

    private function findTeamPlayer($feedPlayerId, $feedTeamId, $sourceName)
    {
        $teamId = $this->findTeamPlayers($feedTeamId, $sourceName);
        if ($teamId !== null) {
            $playerId = $this->mappings->get($sourceName)->getOwnId(Player::class, $feedPlayerId);
            if ($playerId !== null && ! empty($this->teamPlayers[$teamId][$playerId])) {
                if (count($this->teamPlayers[$teamId][$playerId]) == 1) {
                    return $this->teamPlayers[$teamId][$playerId][0];
                } else {
                    foreach ($this->teamPlayers[$teamId][$playerId] as $teamPlayer) {
                        if ($teamPlayer->getActive()) {
                            return $teamPlayer;
                        }
                    }
                    return $this->teamPlayers[$teamId][$playerId][0];
                }
            } else {
                $this->dispatcher->dispatch('Import\TeamPlayer',
                    [
                        $teamId,
                        $sourceName
                    ]);
            }
        }
        
        return null;
    }

    /**
     *
     * @param PlayerStatistics[] $existing
     * @param PlayerStatistics[] $stats
     */
    private function saveStats(array $existing, array $stats)
    {
        $created = [];
        $updated = [];
        
        foreach ($stats as $stat) {
            $found = false;
            $message = get_class($stat->getTournament()) . ":" . $stat->getTournament()->getId() . " " .
                 $stat->getTeam()->getName() . " " . $stat->getPlayer()->getName();
            foreach ($existing as $existingStat) {
                if ($existingStat->equals($stat)) {
                    $found = true;
                    if ($this->repository->hasChanged($existingStat, $stat)) {
                        $updated[] = $this->repository->patchExisting($existingStat, $stat);
                        $this->logger->info(NameUtil::shortClassName(get_class($this)) . ": Updating " . $message);
                    }
                }
            }
            if (! $found) {
                $this->logger->info(NameUtil::shortClassName(get_class($this)) . ": Creating " . $message);
                $created[] = $stat;
            }
        }
        
        if (! empty($created) || ! empty($updated)) {
            $this->repository->saveModels($created, $updated);
        }
    }

    public function importSeason(TournamentSeason $season, $sourceName = null)
    {
        $this->teamPlayers = [];
        $seasonStats = $this->getTournamentStats($season, $sourceName);
        $mergeStages = empty($seasonStats);
        $stages = $this->stageRepository->findByTournamentSeason($season->getId());
        foreach ($stages as $league) {
            if (! $league->hasGroups()) {
                $leagues = [
                    $league
                ];
            } else {
                $leagues = $this->groupRepository->findByStage($league->getId());
            }
            foreach ($leagues as $stage) {
                $stageStats = $this->getTournamentStats($stage);
                if ($mergeStages) {
                    foreach ($stageStats as $id => $statObject) {
                        if (! isset($seasonStats[$id])) {
                            $seasonStats[$id] = $this->repository->createObject(
                                [
                                    'tournament' => $season,
                                    'player' => $statObject->getPlayer(),
                                    'team' => $statObject->getTeam(),
                                    'statistics' => $statObject->getStatistics(),
                                    'shirt_number' => $statObject->getShirtNumber()
                                ]);
                        } else {
                            $seasonStats[$id]->add($statObject->getStatistics());
                        }
                    }
                }
                $existing = $this->repository->findByTournament($stage);
                $this->saveStats($existing, $stageStats);
            }
        }
        
        $existing = $this->repository->findByTournament($season);
        $this->saveStats($existing, $seasonStats);
    }
}

