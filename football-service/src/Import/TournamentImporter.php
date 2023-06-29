<?php
namespace Sportal\FootballApi\Import;

use Sportal\FootballFeedCommon\Tournament\TournamentFeedInterface;
use Sportal\FootballApi\Repository\TournamentRepository;
use Psr\Log\LoggerInterface;
use Sportal\FootballApi\Jobs\JobDispatcherInterface;
use Sportal\FootballApi\Repository\MappingRepositoryContainer;
use Sportal\FootballFeedCommon\FeedContainer;

class TournamentImporter extends Importer
{

    /**
     *
     * @var TournamentFeedInterface[]
     */
    private $feeds;

    /**
     *
     * @var CountryImporter
     */
    private $countryImporter;

    /**
     *
     * @var TournamentSeasonImporter
     */
    private $seasonImporter;

    private $dispatcher;

    public function __construct(TournamentRepository $repository, MappingRepositoryContainer $mappings,
        LoggerInterface $logger, FeedContainer $feeds, CountryImporter $countryImporter,
        TournamentSeasonImporter $seasonImporter, JobDispatcherInterface $dispatcher)
    {
        parent::__construct($repository, $mappings, $logger);
        $this->feeds = $feeds;
        $this->countryImporter = $countryImporter;
        $this->seasonImporter = $seasonImporter;
        $this->dispatcher = $dispatcher;
    }

    public function import(array $feedIds, $sourceName = null)
    {
        foreach ($feedIds as $tournamentId) {
            $tournamentArr = $this->feeds[$sourceName]->getTournament($tournamentId);
            if (! empty($tournamentArr)) {
                $tournamentArr['country'] = $this->countryImporter->importCountry($tournamentArr['country']);
                $tournament = $this->repository->createObject($tournamentArr);
                $tournament = $this->importModel($tournament, $tournamentArr['id'], $sourceName);
                if ($tournament !== null) {
                    $this->dispatcher->dispatch('Import\MlContent',
                        [
                            $tournament
                        ]);
                    foreach ($tournamentArr['tournament_season'] as $season) {
                        $season['tournament_id'] = $tournament->getId();
                        $this->seasonImporter->import($season, $sourceName);
                    }
                }
            }
        }
        $this->seasonImporter->handleChanges();
        $this->handleChanges();
    }

    public function importAll($sourceName = null)
    {
        $ids = $this->mappings->get($sourceName)->getRemoteIds($this->repository->getModelClass());
        $this->import($ids, $sourceName);
    }

    public function importSingle($id, $sourceName = null)
    {
        $remoteId = $this->mappings->get($sourceName)->getRemoteId($this->repository->getModelClass(), $id);
        if ($remoteId !== null) {
            $this->import([
                $remoteId
            ], $sourceName);
        } else {
            throw new \InvalidArgumentException('No tournament with id: ' . $id . ' found');
        }
    }
}