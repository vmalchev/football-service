<?php
namespace Sportal\FootballApi\Import;

use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Infrastructure\Stage\StageTypeDatabaseConverter;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballApi\Repository\TournamentSeasonStageRepository;
use Sportal\FootballFeedCommon\FeedContainer;
use Sportal\FootballFeedCommon\Tournament\TournamentFeedInterface;
use Sportal\FootballApi\Repository\StageGroupRepository;

class TournamentSeasonStageImporter extends Importer
{

    /**
     *
     * @var TournamentSeasonStageRepository
     */
    protected $repository;

    /**
     *
     * @var JobDispatcherInterface
     */
    private $dispatcher;

    private $countryImporter;

    /**
     *
     * @var TournamentFeedInterface[]
     */
    private $feeds;

    private $teamImporter;

    private $groupImporter;

    private $groupRepository;

    public function __construct(TournamentSeasonStageRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, JobDispatcherInterface $dispatcher, CountryImporter $countryImporter,
        FeedContainer $feeds, TeamImporter $teamImporter, StageGroupImporter $groupImporter,
        StageGroupRepository $groupRepository)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->dispatcher = $dispatcher;
        $this->countryImporter = $countryImporter;
        $this->feeds = $feeds;
        $this->teamImporter = $teamImporter;
        $this->groupImporter = $groupImporter;
        $this->groupRepository = $groupRepository;
    }

    public function import(array $stage, $sourceName = null)
    {
        $stage['country'] = $this->countryImporter->importCountry($stage['country']);
        if (! empty($stage['groups'])) {
            $stage['stage_groups'] = count($stage['groups']);
        }

        $stage['type'] = StageTypeDatabaseConverter::fromValue($stage)->getValue();

        $model = $this->repository->createObject($stage);
        $model = $this->importMerge($model, $stage['id'], $sourceName, true,
            function ($model) use ($stage) {
                $this->dispatcher->dispatch('Import\MlContent', [
                    $model
                ]);
            });
        if (! empty($stage['groups'])) {
            foreach ($stage['groups'] as $group) {
                $group['tournament_season_stage_id'] = $model->getId();
                $this->groupImporter->import($group, $sourceName);
            }
        }
        $this->dispatcher->dispatch('Import\TournamentSeasonStageEvents',
            [
                $model->getId()
            ]);
    }

    public function importStages(array $stages, $tournamentSeasonId, $tournamentId, $noRefresh = false, $sourceName = null)
    {
        foreach ($stages as $stage) {
            $stage['tournament_season_id'] = $tournamentSeasonId;
            $stage['tournament_season']['tournament_id'] = $tournamentId;
            $this->import($stage, $sourceName);
        }
        
        if (! $noRefresh) {
            $this->handleChanges();
        }
    }

    public function importTeams($stageId, $sourceName = null)
    {
        $tournamentSeasonStage = $this->repository->find($stageId);
        if ($tournamentSeasonStage !== null) {
            $teamIds = [];
            if ($tournamentSeasonStage->hasGroups()) {
                $groups = $this->groupRepository->findByStage($tournamentSeasonStage->getId());
                foreach ($groups as $group) {
                    $remoteId = $this->mappings->get($sourceName)->getRemoteId(get_class($group), $group->getId());
                    $ids = $this->feeds[$sourceName]->getGroupTeamIds($remoteId);
                    $teamIds = array_merge($teamIds, $ids);
                }
            } else {
                $remoteId = $this->mappings->get($sourceName)->getRemoteId(get_class($tournamentSeasonStage),
                    $tournamentSeasonStage->getId());
                $teamIds = $this->feeds->getDefault()->getTeamIds($remoteId);
            }
            
            $teams = [];
            foreach ($teamIds as $id) {
                if (! isset($teams[$id])) {
                    $teams[$id] = $this->teamImporter->getOrImport($id, false, $sourceName);
                }
            }
            if (! empty($teams)) {
                $this->repository->setTeams($tournamentSeasonStage, $teams);
            }
            return $teams;
        }
    }
}