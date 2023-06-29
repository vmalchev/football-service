<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballApi\Repository\TournamentSeasonRepository;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\TournamentSeasonStageRepository;

class TournamentSeasonImporter extends Importer
{

    /**
     *
     * @var TournamentSeasonStageImporter
     */
    private $stageImporter;

    private $dispatcher;

    private $stageRepository;

    public function __construct(TournamentSeasonRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, TournamentSeasonStageImporter $stageImporter, JobDispatcherInterface $dispatcher,
        TournamentSeasonStageRepository $stageRepository)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->stageImporter = $stageImporter;
        $this->dispatcher = $dispatcher;
        $this->stageRepository = $stageRepository;
    }

    public function import(array $tournamentSeasonArr, $sourceName = null)
    {
        $tournamentSeason = $this->repository->createObject($tournamentSeasonArr);
        $tournamentSeason = $this->importMerge($tournamentSeason, $tournamentSeasonArr['id'], $sourceName);
        if ($tournamentSeason !== null) {
            $this->stageImporter->importStages($tournamentSeasonArr['tournament_season_stage'],
                $tournamentSeason->getId(), $tournamentSeason->getTournamentId(), true, $sourceName);
            if ($this->dispatcher->supports('Import\Standing')) {
                $this->dispatcher->dispatch('Import\Standing',
                    [
                        $this->repository->getModelClass(),
                        $tournamentSeason->getId()
                    ]);
            }
            if ($this->dispatcher->supports('Import\PlayerStatistics')) {
                $this->dispatcher->dispatch('Import\PlayerStatistics',
                    [
                        $tournamentSeason->getId(),
                        $sourceName
                    ]);
            }
            $this->dispatcher->dispatch('Import\TournamentSeasonTeams',
                [
                    $tournamentSeason->getId(),
                    $sourceName
                ]);
        }
    }

    public function importTeams($seasonId, $sourceName = null)
    {
        $season = $this->repository->find($seasonId);
        $stages = $this->stageRepository->findByTournamentSeason($season->getId());
        $seasonTeams = [];
        foreach ($stages as $stage) {
            $teams = $this->stageImporter->importTeams($stage->getId(), $sourceName);
            if ($teams !== null) {
                $seasonTeams = $seasonTeams + $teams;
            }
        }
        if (! empty($seasonTeams)) {
            $this->repository->setTeams($season, $seasonTeams);
        }
    }

    public function handleChanges(callable $handler = null)
    {
        parent::handleChanges($handler);
        $this->stageImporter->handleChanges($handler);
    }
}